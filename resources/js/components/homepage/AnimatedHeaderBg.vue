<template>
    <div id="animated-header-bg__wrapper">
        <div id="animated-header-bg">
            <div class="header-image" v-for="(image, ii) in mutableImages" :key="ii" :style="{ backgroundImage: 'url('+image+')' }">
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            "images",
        ],
        data: () => ({
            tag: "[animated-header-bg]",
            mutableImages: [],
            currentIndex: 0,
        }),
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" images: ", this.images);
                this.initializeData();
                this.initializeBackground();
                this.startAnimation();
            },
            initializeData() {
                if (this.images !== undefined && this.images !== null && this.images.length > 0) {
                    for (let i = 0; i < this.images.length; i++) {
                        this.mutableImages.push(this.images[i]);
                    }
                }
            },
            initializeBackground() {
                console.log("initializing bg");
                $(document).ready(function() {

                    let wrapper = $("#animated-header-bg__wrapper");
                    console.log("wrapper", wrapper);

                    let firstImage = wrapper.find(".header-image")[0];
                    console.log("first", firstImage);

                    $(firstImage).addClass("visible").addClass("top");

                }.bind(this));
            },
            startAnimation() {
                let delay = 10000;
                let wrapper = $("#animated-header-bg__wrapper");
                $(wrapper.find(".header-image")[0]).addClass("top");
                $(wrapper.find(".header-image")[0]).addClass("visible");
                $(wrapper.find(".header-image")[0]).addClass("first-visible");
                window.setInterval(function() {
                    console.log("looping");
                    
                    let lastIndex = this.currentIndex;

                    this.currentIndex += 1;
                    if (this.currentIndex == this.mutableImages.length) this.currentIndex = 0;
                    console.log("last: ", lastIndex, "current", this.currentIndex);

                    let currentImage = $(wrapper.find(".header-image")[this.currentIndex]);
                    let lastImage = $(wrapper.find(".header-image")[lastIndex]);

                    if (lastImage.hasClass("first-visible")) lastImage.removeClass("first-visible");
                    lastImage.removeClass("top");
                    currentImage.addClass("top");
                    currentImage.addClass("visible");
                    
                    setTimeout(function() {
                        lastImage.removeClass("visible");
                    }, (delay/3*2));

				}.bind(this), delay);
            },
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    #animated-header-bg__wrapper {
        width: 100%;
        height: 100%;
        opacity: .8;
        position: absolute;
        background-color: #fff;
        #animated-header-bg {
            width: 100%;
            height: 100%;
            overflow: hidden;
            position: relative;
            .header-image {
                top: 0;
                left: 0;
                opacity: 0;
                width: 150%;
                height: 100%;
                visibility: hidden;
                position: absolute;
                background-size: cover;
                background-repeat: no-repeat;
                background-position: left center;
                -moz-transition: opacity 2s ease;
                -webkit-transition: opacity 2s ease;
                -ms-transition: opacity 2s ease;
                transition: opacity 2s ease;
                &.visible {
                    opacity: 1;
                    z-index: 1;
                    visibility: visible;
                    animation: bg 60s linear infinite;
                    -ms-animation: bg 60s linear infinite;
                    -moz-animation: bg 60s linear infinite;
                    -webkit-animation: bg 60s linear infinite;
                }
                &.first-visible {
                    transition: auto;
                    opacity: 1;
                }
                &.top {
                    z-index: 2;
                }
            }
        }
    }
    @-moz-keyframes bg {
        0% {
            -moz-transform: translateX(0);
            -webkit-transform: translateX(0);
            -ms-transform: translateX(0);
            transform: translateX(0);
        }
        100% {
            -moz-transform: translateX(-25%);
            -webkit-transform: translateX(-25%);
            -ms-transform: translateX(-25%);
            transform: translateX(-25%);
        }
    }
    @-webkit-keyframes bg {
        0% {
            -moz-transform: translateX(0);
            -webkit-transform: translateX(0);
            -ms-transform: translateX(0);
            transform: translateX(0);
        }
        100% {
            -moz-transform: translateX(-25%);
            -webkit-transform: translateX(-25%);
            -ms-transform: translateX(-25%);
            transform: translateX(-25%);
        }
    }
    @-ms-keyframes bg {
        0% {
            -moz-transform: translateX(0);
            -webkit-transform: translateX(0);
            -ms-transform: translateX(0);
            transform: translateX(0);
        }
        100% {
            -moz-transform: translateX(-25%);
            -webkit-transform: translateX(-25%);
            -ms-transform: translateX(-25%);
            transform: translateX(-25%);
        }
    }
    @keyframes bg {
        0% {
            -moz-transform: translateX(0);
            -webkit-transform: translateX(0);
            -ms-transform: translateX(0);
            transform: translateX(0);
        }
        100% {
            -moz-transform: translateX(-25%);
            -webkit-transform: translateX(-25%);
            -ms-transform: translateX(-25%);
            transform: translateX(-25%);
        }
    }
</style>