<template>
    <div id="topnav-search-button">
        <a :href="href" id="topnav-search-button__icon">
            <i class="fas fa-search"></i>
        </a>
    </div>
</template>

<script>
    export default {
        props: [
            "href",
        ],
        data: () => ({
            tag: "[topnav-search-button]",
            
        }),
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" href: ", this.href);
                // console.log(this.tag+" ");
            },
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    #topnav-search-button {
        #topnav-search-button__icon {
            cursor: pointer;
            transition: all .3s;
            color: rgba(0, 0, 0, 0.75);
            &:hover {
                color: rgba(0, 0, 0, 1);
            }
        }
    }
</style>