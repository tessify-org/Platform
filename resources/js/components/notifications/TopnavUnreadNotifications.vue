<template>
    <div id="topnav-unread-notifications__wrapper">
        <a :href="href" id="topnav-unread-notifications" :class="{ active: mutableCount > 0, light: isLight }">
            <span id="topnav-unread-notifications__icon">
                <i class="fas fa-flag"></i>
            </span>
            <span id="topnav-unread-notifications__count">
                {{ mutableCount }}
            </span>
        </a>
    </div>
</template>

<script>
    export default {
        props: [
            "count",
            "href",
            "light",
        ],
        data: () => ({
            tag: "[topnav-unread-notifications]",
            mutableCount: 0,
        }),
        computed: {
            isLight() {
                return this.light === true;
            },
        },
        methods: {
            initialize() {
                // console.log(this.tag+" initializing");
                // console.log(this.tag+" count: ", this.count);
                // console.log(this.tag+" href: ", this.href);
                // console.log(this.tag+" light: ", this.light);
                this.initializeData();
            },
            initializeData() {
                this.mutableCount = this.count;
            },
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    #topnav-unread-notifications__wrapper {
        #topnav-unread-notifications {
            display: flex;
            color: #000000;
            // padding: 6px 10px;
            border-radius: 3px;
            flex-direction: row;
            align-items: center;
            transition: all .3s;
            text-decoration: none;
            box-sizing: border-box;
            // background-color: hsl(0, 0%, 95%);
            &.light {
                color: hsl(0, 0%, 90%);
                &:hover {
                    color: #ffffff;
                }   
            }
            &.active {
                color: #f84343;
                // background-color: #f84343;
                &:hover {
                    color: #df1212;
                    // background-color: #df1212;
                }
            }
            &:hover {
                color: hsl(0, 0%, 10%);
                // background-color: hsl(0, 0%, 90%);
            }
            #topnav-unread-notifications__icon {
                font-size: 13px;
            }
            #topnav-unread-notifications__count {
                font-size: .8em;
                margin: 0 0 0 5px;
            }
        }
    }
</style>