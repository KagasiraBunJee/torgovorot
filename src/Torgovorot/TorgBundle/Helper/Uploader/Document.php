<?php
namespace Torgovorot\TorgBundle\Helper\Uploader;


use Symfony\Component\HttpFoundation\File\UploadedFile;
use Torgovorot\TorgBundle\Entity\AdvImages;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Torgovorot\TorgBundle\Entity\Banners;
use Torgovorot\TorgBundle\Helper\Uploader\SimpleImage;
use Torgovorot\TorgBundle\Helper\Uploader\upload;

class Document 
{
    /**
     * @Assert\File(maxSize="300k")
     */
    private $file;
    
    private $owner_id;
    
    private $name;
    
    private $em;
    
    private $mtype = "image";
    
    private $file_type = 0;
    
    public function __construct(UploadedFile $file = null, $owner_id = 0, EntityManager $em1 = null)
    {
        
        $this->file = $file;
        
        $this->owner_id = $owner_id;
        
        $this->em = $em1;
    }
    
    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (is_file($this->getAbsolutePath())) {
            // store the old name to delete after the update
            $this->temp = $this->getAbsolutePath();
        } else {
            $this->path = 'initial';
        }
    }
    public $path;
    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }
    
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            $this->path = $this->getFile()->guessExtension();
        }
    }

    
        /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload($params = array())
    {
        if (null === $this->getFile()) {
            return;
        }
        
        $photo = new AdvImages();
        
        $banner = new Banners();
        
        $return_arr = array();
        
        if(!empty($params))
        {
            foreach($params as $key=>$value) $$key = $value;
            //owner id
            if(isset($owner))
            {
                $photo->setOwnerId($owner);
                
            }
            // announce of what we receiving (banner, photo)
            if($type)
            {
                $this->mtype = $type;
            }
            
        }
        
        //Get File Size
        $size = $this->getFile()->getSize();
        //mime type
        $mtype = $this->getFile()->getMimeType();
        
        //Create new image name
        $name = uniqid();
        
        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->temp);
            // clear the temp image path
            $this->temp = null;
        }
        
        // you must throw an exception here if the file cannot be moved
        // so that the entity is not persisted to the database
        // which the UploadedFile move() method does
        $filename = $name.".".$this->getFile()->guessExtension();
        
        switch($mtype)
        {
            case "application/x-shockwave-flash":
                if($size < (10*1024*1024) && $this->mtype == "banner")
                {
                    
                    $banner->setType(2);
                    $this->file_type = 2;
                    
                    $this->getFile()->move(
                        $this->getUploadRootDir(),
                        $filename
                        //$this->getFile()->guessExtension()
                    );
                    //$banner->setObjUrl($filename);
                    //$this->em->persist($banner);
                    //$this->em->flush();
   

                    $size = getimagesize($this->getUploadRootDir().'/'.$filename);
                    $return_arr['x'] = $size[0];
                    $return_arr['y'] = $size[1];
                    $return_arr['type'] = $this->file_type;
                    $return_arr['file_name'] = $filename;
                    
                    return $return_arr;
                }
                break;
            case "image/gif":
            case "image/jpeg":
            case "image/pjpeg":
            case "image/png":
                if($size < (5*1024*1024))
                {
                    
                    $banner->setType(1);
                    
                    switch($this->mtype)
                    {
                        
                        case "banner":
                            $this->file_type = 1;
                            $banner->setObjUrl($filename);
                            //$this->em->persist($banner);
                            //$this->em->flush();
                            $this->getFile()->move(
                                $this->getUploadRootDir(),
                                $name.".".$this->getFile()->guessExtension()
                                //$this->getFile()->guessExtension()
                            );
                            $size = getimagesize($this->getUploadRootDir().'/'.$filename);
                            $return_arr['x'] = $size[0];
                            $return_arr['y'] = $size[1];
                            $return_arr['type'] = $this->file_type;
                            $return_arr['file_name'] = $filename;
                            return $return_arr;
                            break;
                        case "photo":
                            $this->file_type = 0;
                            $photo->setIName($filename);
                            
                            $this->em->persist($photo);
                            $this->em->flush();
        
                            
                            
                            $this->getFile()->move(
                                $this->getUploadRootDir(),
                                $filename
                                //$this->getFile()->guessExtension()
                            );
                            
                            //resize
                            $this->resizeCurrent($filename);
                            
                            $this->setFile(null);
                            return $photo->getId();
                            break;
                    }
                }
                break;
        }
        
        $this->setFile(null);
        
        return false;
    }
    
    public function download($url)
    {
        $photo = new AdvImages();
        
        $new_name = uniqid();
        
        $image_arr = explode("/", $url);
        
        $image_name = $image_arr[(count($image_arr)-1)];
        
        $image_name_arr = explode(".",$image_name);
        
        $img_name = "";
        
        foreach($image_name_arr as $key=>$parts)
        {
            if($key != (count($image_name_arr)-1) )
            {
                $img_name .= $parts;
            }
        }
        
        $img_ext = $image_name_arr[(count($image_name_arr)-1)];
        
        $path = $this->getUploadRootDir()."/$new_name.$img_ext";
        
        file_put_contents($path, file_get_contents($url));
        
        $photo->setIName($new_name.".$img_ext");
        
        $this->em->persist($photo);
        $this->em->flush();
        
        //$this->resizeCurrent($new_name.".jpg");
        
        return $photo->getId();
    }
    
    public function resizeCurrent($image)
    {
        $name = explode(".", $image);
        $dir = $this->getUploadRootDir();
        //small
        $small_files = scandir($dir."/small");
        //average
        $average = scandir($dir."/average");
        //item_image
        $item_image = scandir($dir."/item_image");
        //preview
        $preview = scandir($dir."/preview");
        //event image
        $event_image = scandir($dir."/event_image");
        //portrait
        $portrait_image = scandir($dir."/portrait_image");
        
        $imageinfo = getimagesize($dir."/".$image);
        if(!in_array($name[0].".jpg", $average))
        {
            $resize = new upload($this->getUploadRootDir()."/".$image);
            if($resize->uploaded)
            {
                $resize->file_new_name_body = $name[0];
                if($imageinfo[0] > '120' || $imageinfo[1] > '164') { $resize->image_resize = true; } 
                $resize->image_convert = "jpg";
                $resize->image_y = 120;
                $resize->image_x = 164;
                $resize->file_overwrite = true;
                $resize->image_ratio_fill      = 'C';
                $resize->image_background_color = '#FFFFFF';
                $resize->jpeg_quality = 100;
                $resize->Process($this->getUploadRootDir()."/average/");
            }
        }
        
        if(!in_array($name[0].".jpg", $small_files))
        {
            $resize = new upload($this->getUploadRootDir()."/".$image);
            if($resize->uploaded)
            {
                $resize->file_new_name_body = $name[0];
                if($imageinfo[0] > '120' || $imageinfo[1] > '80') { $resize->image_resize = true; }
                $resize->image_convert = "jpg";
                $resize->image_y = 80;
                $resize->image_x = 120;
                $resize->file_overwrite = true;
                $resize->image_ratio_fill      = 'C';
                $resize->image_background_color = '#FFFFFF';
                $resize->jpeg_quality = 100;
                $resize->Process($this->getUploadRootDir()."/small/");
            }
        }
        
        if(!in_array($name[0].".jpg", $item_image))
        {
            $resize = new upload($this->getUploadRootDir()."/".$image);
            if($resize->uploaded)
            {
                $resize->file_new_name_body = $name[0];
                if($imageinfo[0] > '435' || $imageinfo[1] > '281') { $resize->image_resize = true; }
                $resize->image_convert = "jpg";
                $resize->image_y = 281;
                $resize->image_x = 435;
                $resize->file_overwrite = true;
                $resize->image_ratio_fill      = 'C';
                $resize->image_background_color = '#FFFFFF';
                $resize->jpeg_quality = 100;
                $resize->Process($this->getUploadRootDir()."/item_image/");
            }
        }
        
        if(!in_array($name[0].".jpg", $preview))
        {
            $resize = new upload($this->getUploadRootDir()."/".$image);
            if($resize->uploaded)
            {
                $resize->file_new_name_body = $name[0];
                if($imageinfo[0] > '94' || $imageinfo[1] > '60') { $resize->image_resize = true; }
                $resize->image_convert = "jpg";
                $resize->image_y = 60;
                $resize->image_x = 94;
                $resize->file_overwrite = true;
                $resize->image_ratio_fill      = 'C';
                $resize->image_background_color = '#FFFFFF';
                $resize->jpeg_quality = 100;
                $resize->Process($this->getUploadRootDir()."/preview/");
            }
        }
        
        if(!in_array($name[0].".jpg", $event_image))
        {
            $resize = new upload($this->getUploadRootDir()."/".$image);
            if($resize->uploaded)
            {
                $resize->file_new_name_body = $name[0];
                if($imageinfo[0] > '134' || $imageinfo[1] > '51') { $resize->image_resize = true; }
                $resize->image_convert = "jpg";
                $resize->image_y = 51;
                $resize->image_x = 134;
                $resize->file_overwrite = true;
                $resize->image_ratio_fill      = 'C';
                $resize->image_background_color = '#FFFFFF';
                $resize->jpeg_quality = 100;
                $resize->Process($this->getUploadRootDir()."/event_image/");
            }
        }
        
        if(!in_array($name[0].".jpg", $portrait_image))
        {
            $resize = new upload($this->getUploadRootDir()."/".$image);
            if($resize->uploaded)
            {
                $resize->file_new_name_body = $name[0];
                if($imageinfo[0] > '200' || $imageinfo[1] > '286') { $resize->image_resize = true; }
                $resize->image_convert = "jpg";
                $resize->image_y = 286;
                $resize->image_x = 200;
                $resize->file_overwrite = true;
                $resize->image_ratio_fill      = 'C';
                $resize->image_background_color = '#FFFFFF';
                $resize->jpeg_quality = 100;
                $resize->Process($this->getUploadRootDir()."/portrait_image/");
            }
        }
    }
    
    public function resizeAll()
    {
        $this->file_type = 0;
        
        $dir = $this->getUploadRootDir();
        
        $files = scandir($dir);
        //small
        $small_files = scandir($this->getUploadRootDir().'/small');
        //average
        $average = scandir($dir."/average");
        //item_image
        $item_image = scandir($dir."/item_image");
        //preview
        $preview = scandir($dir."/preview");
        //event image
        $event_image = scandir($dir."/event_image");
        //portrait
        $portrait_image = scandir($dir."/portrait_image");
        
        foreach ($files as $file)
        {
            $name = explode(".",$file);
            if($file != "." and $file != ".." and $file != "")
            {
                $full_file = $dir."/".$file;
                if(stripos($file, ".") !== FALSE)
                {
                    $imageinfo = getimagesize($full_file);
                    if(!in_array($name[0].".jpg", $average))
                    {
                        //average
                        $resize = new upload($this->getUploadRootDir()."/".$file);
                        //echo file_exists($this->getUploadRootDir()."/".$file);
                        if($resize->uploaded)
                        {
                            $resize->file_new_name_body = $name[0];
                            if($imageinfo[0] > '120' || $imageinfo[1] > '164') { $resize->image_resize = true; } 
                            $resize->image_convert = "jpg";
                            $resize->image_y = 120;
                            $resize->image_x = 164;
                            $resize->file_overwrite = true;
                            $resize->image_ratio_fill      = 'C';
                            $resize->image_background_color = '#FFFFFF';
                            $resize->jpeg_quality = 100;
                            $resize->Process($this->getUploadRootDir()."/average/");
                        }
                    }
                    if(!in_array($name[0].".jpg", $small_files))
                    {
                        //small
                        $resize = new upload($this->getUploadRootDir()."/".$file);
                        if($resize->uploaded)
                        {
                            $resize->file_new_name_body = $name[0];
                            if($imageinfo[0] > '120' || $imageinfo[1] > '80') { $resize->image_resize = true; } 
                            $resize->image_convert = "jpg";
                            $resize->image_y = 80;
                            $resize->image_x = 120;
                            $resize->file_overwrite = true;
                            $resize->image_ratio_fill      = 'C';
                            $resize->image_background_color = '#FFFFFF';
                            $resize->jpeg_quality = 100;
                            $resize->Process($this->getUploadRootDir()."/small/");
                        }
                    }
                    if(!in_array($name[0].".jpg", $item_image))
                    {
                        //item_image
                        $resize = new upload($this->getUploadRootDir()."/".$file);
                        if($resize->uploaded)
                        {
                            $resize->file_new_name_body = $name[0];
                            if($imageinfo[0] > '435' || $imageinfo[1] > '281') { $resize->image_resize = true; } 
                            $resize->image_convert = "jpg";
                            $resize->image_y = 281;
                            $resize->image_x = 435;
                            $resize->file_overwrite = true;
                            $resize->image_ratio_fill      = 'C';
                            $resize->image_background_color = '#FFFFFF';
                            $resize->jpeg_quality = 100;
                            $resize->Process($this->getUploadRootDir()."/item_image/");
                        }
                    }
                    if(!in_array($name[0].".jpg", $preview))
                    {
                        //item_image
                        $resize = new upload($this->getUploadRootDir()."/".$file);
                        if($resize->uploaded)
                        {
                            $resize->file_new_name_body = $name[0];
                            if($imageinfo[0] > '94' || $imageinfo[1] > '60') { $resize->image_resize = true; } 
                            $resize->image_convert = "jpg";
                            $resize->image_y = 60;
                            $resize->image_x = 94;
                            $resize->file_overwrite = true;
                            $resize->image_ratio_fill      = 'C';
                            $resize->image_background_color = '#FFFFFF';
                            $resize->jpeg_quality = 100;
                            $resize->Process($this->getUploadRootDir()."/preview/");
                        }
                    }
                    if(!in_array($name[0].".jpg", $event_image))
                    {
                        //item_image
                        $resize = new upload($this->getUploadRootDir()."/".$file);
                        if($resize->uploaded)
                        {
                            $resize->file_new_name_body = $name[0];
                            if($imageinfo[0] > '134' || $imageinfo[1] > '51') { $resize->image_resize = true; } 
                            $resize->image_convert = "jpg";
                            $resize->image_y = 51;
                            $resize->image_x = 134;
                            $resize->file_overwrite = true;
                            $resize->image_ratio_fill      = 'C';
                            $resize->image_background_color = '#FFFFFF';
                            $resize->jpeg_quality = 100;
                            $resize->Process($this->getUploadRootDir()."/event_image/");
                        }
                    }
                    if(!in_array($name[0].".jpg", $portrait_image))
                    {
                        $resize = new upload($this->getUploadRootDir()."/".$file);
                        if($resize->uploaded)
                        {
                            $resize->file_new_name_body = $name[0];
                            if($imageinfo[0] > '200' || $imageinfo[1] > '286') { $resize->image_resize = true; }
                            $resize->image_convert = "jpg";
                            $resize->image_y = 286;
                            $resize->image_x = 200;
                            $resize->file_overwrite = true;
                            $resize->image_ratio_fill      = 'C';
                            $resize->image_background_color = '#FFFFFF';
                            $resize->jpeg_quality = 100;
                            $resize->Process($this->getUploadRootDir()."/portrait_image/");
                        }
                    }
                }
            }
        }
    }

    /**
     * @ORM\PreRemove()
     */
    public function storeFilenameForRemove()
    {
        $this->temp = $this->getAbsolutePath();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if (isset($this->temp)) {
            unlink($this->temp);
        }
    }

    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->id.'.'.$this->path;
        
    }
    
    public function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        
        return __DIR__.'/../../../../../web/'.$this->getUploadDir();
        //return __DIR__;
    }
    
    public function getCommonDir()
    {
        
        return $this->getUploadDir();
    }

    public function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        switch($this->file_type)
        {
            case 0:
                return 'uploads/photo';
                break;
            case 1:
                return 'uploads/banners/img';
                break;
            case 2:
                return 'uploads/banners/flash';
                break;
            case 3:
                return 'uploads/photo/average';
                break;
            case 4:
                return 'uploads/photo/small';
                break;
        }
    }
    
    public function uploadToBase()
    {
        
    }
}
