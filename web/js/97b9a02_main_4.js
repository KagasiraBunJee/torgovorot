$(function () {
    select("select");
    checkbox(".toolbox input");

    $(".shadow").height($(".main-right").height());

    $(".quest > a").click(function (e) {
        e.preventDefault();
        var parent = $(this).closest(".quest");
        parent.toggleClass("open");
    });

    $(".bookmark").click(function () {
        var url = window.document.location;
        var title = window.document.title;

        function bookmark(a) {
            a.href = url;
            a.rel = 'sidebar';
            a.title = title;
            return true;
        }

        bookmark(this);
        if (document.all) {
            window.external.AddFavorite(location.href, document.title);
            return false;
        }
        Modernizr.addTest('webkit', function () {
            return new RegExp(" AppleWebKit/").test(navigator.userAgent);
        });
        if (Modernizr.webkit) {
            alert("Нажмите Ctrl+D (Cmd+D)");
            return false;
        }

        return true;
    });

    $(".files a").click(function (e) {
        e.preventDefault();
        var th = $(this);
        var parentFiles = th.closest(".files");
        var span = parentFiles.find("span");
        var file = parentFiles.find("input[type=file]");

        file.click();

    });

    var files = $(".files input[type=file]");
    files.val();
    files.change(function (e) {
        var th = $(this);
        var parentFiles = th.closest(".files");
        var span = parentFiles.find(">span");

        span.text(th.val());

    });

    $(".add-input").click(function (e) {
        e.preventDefault();
        var th = $(this);
        var clone = th.closest("div").find("input[type=text]:first").clone();
        clone.val("");

        th.before(clone);

    });

    $(".load-photo").click(function (e) {
        e.preventDefault();

        $(".photos-file input").click();
    });

    $(".photos-file input").change(function () {
        $(".photos-file span").remove();
        var files = $(".photos-file input");
        $.each(files.get(0).files, function (i, n) {
            $(".photos-file").append($("<span/>", {text: n.name}));
        });
    });

    $(".search-tabs a").click(function (e) {
        e.preventDefault();
        $(".search-tabs a").removeClass("active");
        $(this).addClass("active");
    });

    var categoryLi = $(".category > li");
    var subCategory = $(".subcategory ul");
    categoryLi.click(function (e) {
        e.preventDefault();

        var $this = $(this);
        var idCategory = $this.attr("data-category");
        categoryLi.removeClass("active");
        $this.addClass("active");

        subCategory.hide();
        subCategory.filter("[data-category=" + idCategory + "]").show();

    });

    if (categoryLi.get(0)) {
        categoryLi.get(0).click();
    }

    subCategory.find("li").click(function (e) {
        e.preventDefault();
        var $this = $(this);
        var curId = $this.attr("data-subcategory");
        var categoryText = categoryLi.filter(".active").text();
        var subcategoryText = $this.text();

        var categoryTextDiv = $(".categoryText");

        categoryTextDiv.find("span:eq(1)").text(categoryText);
        categoryTextDiv.find("span:eq(2)").text(subcategoryText);
        categoryTextDiv.find("input[type=hidden]").val(curId);

        $(".categoryText, .categoryEdit:not(.btn)").show();
        $(".categoryEdit.btn").hide();
        $(".dialog, .ph").addClass("hide");
    });

    $(".dclose").click(function (e) {
        e.preventDefault();
        $(".dialog, .ph").addClass("hide");
    });

    $(".categoryEdit").click(function (e) {
        e.preventDefault();

        $(".ph, .dialog").removeClass("hide");
    });

    $(".print").click(function (e) {
        e.preventDefault();
        window.print();
    });

    $('.zoom').elevateZoom({
        zoomType: "inner",
        cursor: "crosshair",
        zoomWindowFadeIn: 500,
        zoomWindowFadeOut: 750
    });

    var allimg = $(".photos > div:eq(1) img");
    allimg.click(function (e) {
        e.preventDefault();
        var $this = $(this);
        var smallImgSrc = $this.attr("src");
        var bigImgSrc = $this.attr("data-zoom-image");

        allimg.removeClass("active");
        $this.addClass("active");

        var bigImg = $(".photos > div:eq(0) img");
        bigImg.attr("src", smallImgSrc);
        bigImg.attr("data-zoom-image", bigImgSrc);

        bigImg.removeData();
        $(".zoomContainer").remove();
        bigImg.elevateZoom({
            zoomType: "inner",
            cursor: "crosshair",
            zoomWindowFadeIn: 500,
            zoomWindowFadeOut: 750
        });
    });

    function select(clss) {
        $(clss).wrap("<div class='select'/>");
        var all = $(".select");


        all.find("select").wrap('<div class="inline-select"></div>');
        all.find(".inline-select").append('<div class="title-select">' +
            '<span></span><div class="cls"></div></div>' +
            '<div class="menu-select"><ul></ul></div>');
        $.each(all.find(".inline-select"), function (i, n) {
            var val = $(n).find("select option:selected").text();
            $(n).find(".title-select span").text(val);
            $(n).find(".menu-select ul").empty();
            $.each($(n).find("select option"), function (j, k) {
                $(n).find(".menu-select ul").append('<li data-val="' + $(k).attr("value") + '">' + $(k).text() + '</li>');
            });
            $(n).find(".title-select").addClass($(n).find("select").attr("class"));
            $(n).find(".menu-select").css("min-width", $(n).find(".title-select").innerWidth());


        });
        all.find(".inline-select ul").find("li:last").addClass("last");
        all.find(".inline-select select").hide();
        all.find(".title-select").click(function () {
            var par = $(this).parents(".inline-select");
            par.find(".menu-select").show();
            par.addClass("open");
        });
        all.find(".inline-select").mouseleave(function () {
            all.find(".menu-select").hide();
            $(this).removeClass("open");
        });
        all.find(".inline-select li").click(function () {
            var index = $(this).index();
            var val = $(this).attr("data-val");
            var par = $(this).parents(".inline-select");
            par.find("select").val(val);
            par.find(".title-select span").text($(this).text());
            par.find(".menu-select").css("min-width", par.find(".title-select").innerWidth());

            par.removeClass("open");
            par.find(".menu-select").hide();
        });
    }

    function checkbox(selector) {
        var collect = $(selector).filter("[type=checkbox]");

        $.each(collect, function (i, n) {
            var ch = $(n);

            ch.wrap($("<div/>", {
                "class": "ch-checkbox"
            }));

            ch.hide();

            var parentDiv = ch.closest("div");
            if (ch.prop('checked')) {
                parentDiv.addClass("checked");
                parentDiv.next("label").toggleClass("checked");
            }

            parentDiv.click(function (e) {
                $(this).toggleClass("checked");
                $(this).next("label").toggleClass("checked");
                var check = $(this).find("[type=checkbox]");

                check.prop("checked", !check.prop("checked"));
            })

        });
    }
});