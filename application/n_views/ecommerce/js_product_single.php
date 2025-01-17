<script>


    /* 1 of 4 : SWIPER ################################### */
    var galleryThumbs = new Swiper('.gallery-thumbs', {
        spaceBetween: 10,
        slidesPerView: 5,
        freeMode: true,
        watchSlidesVisibility: true,
        watchSlidesProgress: true,
    });

    var mySwiper = new Swiper(".gallery-top", {

        /* slidesPerView || auto - if you want to set width by css like flickity.js layout - in this case width:80% by CSS */
        slidesPerView: 1,
        spaceBetween: 10,
        centeredSlides: true,
        slideToClickedSlide: false,
// If we need pagination
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
            renderBullet: function (index, className) {
                return '<span class="' + className + '">' + (index + 1) + "</span>";
            }
        },
// Navigation arrows
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        thumbs: {
            swiper: galleryThumbs
        },
// keyboard control
        keyboard: {
            enabled: true,
        }
    });

    // 2 of 4 : PHOTOSWIPE #######################################
    // https://photoswipe.com/documentation/getting-started.html //

    var initPhotoSwipeFromDOM = function (gallerySelector) {
// parse slide data (url, title, size ...) from DOM elements
// (children of gallerySelector)
        var parseThumbnailElements = function (el) {
            console.log(1);
            var thumbElements = el.childNodes,
                numNodes = thumbElements.length,
                items = [],
                figureEl,
                linkEl,
                size,
                item;

            for (var i = 0; i < numNodes; i++) {
                figureEl = thumbElements[i]; // <figure> element

                // include only element nodes
                if (figureEl.nodeType !== 1) {
                    continue;
                }

                linkEl = figureEl.children[0]; // <a> element


                size = linkEl.getAttribute("data-size").split("x");

                // create slide object
                item = {
                    src: linkEl.getAttribute("href"),
                    w: parseInt(size[0], 10),
                    h: parseInt(size[1], 10)
                };

                if (figureEl.children.length > 1) {
                    // <figcaption> content
                    item.title = figureEl.children[1].innerHTML;
                }

                if (linkEl.children.length > 0) {
                    // <img> thumbnail element, retrieving thumbnail url
                    item.msrc = linkEl.children[0].getAttribute("src");
                }

                item.el = figureEl; // save link to element for getThumbBoundsFn
                items.push(item);
            }

            return items;
        };

        // find nearest parent element
        var closest = function closest(el, fn) {
            return el && (fn(el) ? el : closest(el.parentNode, fn));
        };

        // triggers when user clicks on thumbnail
        var onThumbnailsClick = function (e) {
            e = e || window.event;
            e.preventDefault ? e.preventDefault() : (e.returnValue = false);

            var eTarget = e.target || e.srcElement;

            // find root element of slide
            var clickedListItem = closest(eTarget, function (el) {
                return el.tagName && el.tagName.toUpperCase() === "LI";
            });

            if (!clickedListItem) {
                return;
            }

            // find index of clicked item by looping through all child nodes
            // alternatively, you may define index via data- attribute
            var clickedGallery = clickedListItem.parentNode,
                childNodes = clickedListItem.parentNode.childNodes,
                numChildNodes = childNodes.length,
                nodeIndex = 0,
                index;

            for (var i = 0; i < numChildNodes; i++) {
                if (childNodes[i].nodeType !== 1) {
                    continue;
                }

                if (childNodes[i] === clickedListItem) {
                    index = nodeIndex;
                    break;
                }
                nodeIndex++;
            }

            if (index >= 0) {
                // open PhotoSwipe if valid index found
                openPhotoSwipe(index, clickedGallery);
            }
            return false;
        };

        // parse picture index and gallery index from URL (#&pid=1&gid=2)
        var photoswipeParseHash = function () {
            var hash = window.location.hash.substring(1),
                params = {};

            if (hash.length < 5) {
                return params;
            }

            var vars = hash.split("&");
            for (var i = 0; i < vars.length; i++) {
                if (!vars[i]) {
                    continue;
                }
                var pair = vars[i].split("=");
                if (pair.length < 2) {
                    continue;
                }
                params[pair[0]] = pair[1];
            }

            if (params.gid) {
                params.gid = parseInt(params.gid, 10);
            }

            return params;
        };

        var openPhotoSwipe = function (
            index,
            galleryElement,
            disableAnimation,
            fromURL
        ) {
            var pswpElement = document.querySelectorAll(".pswp")[0],
                gallery,
                options,
                items;

            items = parseThumbnailElements(galleryElement);
            console.log(items);

            // #################### 3/4 define photoswipe options (if needed) ####################
            // https://photoswipe.com/documentation/options.html //
            options = {
                /* "showHideOpacity" uncomment this If dimensions of your small thumbnail don't match dimensions of large image */
                //showHideOpacity:true,

                // Buttons/elements
                closeEl: true,
                captionEl: true,
                fullscreenEl: true,
                zoomEl: true,
                shareEl: false,
                counterEl: true,
                arrowEl: true,
                preloaderEl: true,
                // define gallery index (for URL)
                galleryUID: galleryElement.getAttribute("data-pswp-uid"),
                getThumbBoundsFn: function (index) {
                    // See Options -> getThumbBoundsFn section of documentation for more info
                    var thumbnail = items[index].el.getElementsByTagName("img")[0], // find thumbnail
                        pageYScroll =
                            window.pageYOffset || document.documentElement.scrollTop,
                        rect = thumbnail.getBoundingClientRect();

                    return {x: rect.left, y: rect.top + pageYScroll, w: rect.width};
                }
            };

            // PhotoSwipe opened from URL
            if (fromURL) {
                if (options.galleryPIDs) {
                    // parse real index when custom PIDs are used
                    // http://photoswipe.com/documentation/faq.html#custom-pid-in-url
                    for (var j = 0; j < items.length; j++) {
                        if (items[j].pid == index) {
                            options.index = j;
                            break;
                        }
                    }
                } else {
                    // in URL indexes start from 1
                    options.index = parseInt(index, 10) - 1;
                }
            } else {
                options.index = parseInt(index, 10);
            }

            // exit if index not found
            if (isNaN(options.index)) {
                return;
            }

            if (disableAnimation) {
                options.showAnimationDuration = 0;
            }

            // Pass data to PhotoSwipe and initialize it
            gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
            gallery.listen('gettingData', function (index, item) {
                if (item.w < 1 || item.h < 1) { // unknown size
                    var img = new Image();
                    img.onload = function () { // will get size after load
                        item.w = this.width; // set image width
                        item.h = this.height; // set image height
                        gallery.invalidateCurrItems(); // reinit Items
                        gallery.updateSize(true); // reinit Items
                    }
                    img.src = item.src; // let's download image
                }
            });
            gallery.init();

            /* ########### PART 4 - EXTRA CODE  ########### */
            /* EXTRA CODE (NOT FROM photoswipe CORE) -
            1/2. UPDATE SWIPER POSITION TO THE CURRENT ZOOM_IN IMAGE (BETTER UI) */
            // photoswipe event: Gallery unbinds events
            // (triggers before closing animation)
            gallery.listen("unbindEvents", function () {
                // The index of the current photoswipe slide
                var getCurrentIndex = gallery.getCurrentIndex();
                // Update position of the slider
                mySwiper.slideTo(getCurrentIndex, 0, false);
                // 2/2. Start swiper autoplay (on close - if swiper autoplay is true)
                mySwiper.autoplay.start();
            });
            // 2/2. Extra Code (Not from photoswipe) - swiper autoplay stop when image in zoom mode (When lightbox is open) */
            gallery.listen('initialZoomIn', function () {
                if (mySwiper.autoplay.running) {
                    mySwiper.autoplay.stop();
                }
            });
        };

        // loop through all gallery elements and bind events
        var galleryElements = document.querySelectorAll(gallerySelector + ' .swiper-slide');


        for (var i = 0, l = galleryElements.length; i < l; i++) {
            galleryElements[i].setAttribute("data-pswp-uid", i + 1);
            galleryElements[i].onclick = onThumbnailsClick;
        }

        // Parse URL and open gallery if it contains #&pid=3&gid=1
        var hashData = photoswipeParseHash();
        console.log(hashData)
        if (hashData.pid && hashData.gid) {
            openPhotoSwipe(hashData.pid, galleryElements[hashData.gid - 1], true, true);
        }
    };

    // execute above function
    initPhotoSwipeFromDOM(".my-gallery");

</script>

