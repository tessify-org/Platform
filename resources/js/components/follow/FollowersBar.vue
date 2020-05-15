<template>
    <div class="followers-bar__wrapper">

        <!-- Bar -->
        <div class="followers-bar elevation-1" @click="onClickView">
            <div class="followers-bar__count">
                {{ mutableFollowers.length }}
            </div>
            <div class="followers-bar__text">
                {{ labelText }}
            </div>
        </div>

        <!-- Dialog -->
        <v-dialog v-model="dialogs.view.show" width="400">
            <div class="dialog" v-if="this.dialogs.view.index !== null">
                <div class="dialog__close-button" @click="dialogs.view.show = false">
                    <i class="fas fa-times"></i>
                </div>
                <div class="dialog-content">
                    <h3 class="dialog-title">{{ dialogTitleText }}</h3>
                    <div class="follower-list" v-if="mutableFollowers.length > 0">
                        <a class="follower" v-for="(follower, fi) in mutableFollowers" :key="fi" :href="follower.profile_href">
                            <span class="follower-avatar" :style="{ backgroundImage: 'url('+follower.avatar_url+')' }"></span>
                            <span class="follower-name">
                                {{ follower.formatted_name }}
                            </span>
                        </a>
                    </div>
                    <div class="dialog-text no-followers" v-if="mutableFollowers.length === 0">
                        {{ noFollowersText }}
                    </div>
                </div>
            </div>
        </v-dialog>

    </div>
</template>

<script>
    export default {
        props: [
            "followers",
            "labelText",
            "noFollowersText",
            "dialogTitleText",
        ],
        data: () => ({
            tag: "[followers-bar]",
            mutableFollowers: [],
            dialogs: {
                view: {
                    show: false,
                }
            }
        }),
        methods: {
            initialize() {
                console.log(this.tag+" initialize");
                console.log(this.tag+" followers: ", this.followers);
                console.log(this.tag+" label text: ", this.labelText);
                console.log(this.tag+" no followers text: ", this.noFollowersText);
                console.log(this.tag+" dialog title text: ", this.dialogTitleText);
                this.initializeData();
            },
            initializeData() {
                if (this.followers !== undefined && this.followers !== null && this.followers.length > 0) {
                    for (let i = 0; i < this.followers.length; i++) {
                        this.mutableFollowers.push(this.followers[i]);
                    }
                }
            },
            onClickView() {
                this.dialogs.view.show = true;
            }
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    .followers-bar__wrapper {
        .followers-bar {
            display: flex;
            overflow: hidden;
            border-radius: 3px;
            transition: all .3s;
            flex-direction: row;
            background-color: #fff;
            &:hover {
                cursor: pointer;
                background-color: hsl(0, 0%, 95%);
                .followers-bar__count {
                    background-color: hsl(0, 0%, 90%);
                }
            }
            .followers-bar__count {
                height: 30px;
                display: flex;
                padding: 0 10px;
                transition: all .3s;
                flex-direction: row;
                align-items: center;
                box-sizing: border-box;
                justify-content: center;
                background-color: hsl(0, 0%, 95%);
            }
            .followers-bar__text {
                height: 30px;
                display: flex;
                padding: 0 10px;
                flex-direction: row;
                align-items: center;
            }
        }
    }
    .follower-list {
        .follower {
            color: #000;
            display: flex;
            padding: 10px 0;
            flex-direction: row;
            align-items: center;
            text-decoration: none;
            transition: all .3s;
            box-sizing: border-box;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            &:last-child {
                border-bottom: 0;
            }
            &:hover {
                color: #154bee;
            }
            .follower-avatar {
                width: 30px;
                height: 30px;
                border-radius: 15px;
                background-size: contain;
                background-repeat: no-repeat;
                background-position: center center;
                background-color: hsl(0, 0%, 90%);
            }
            .follower-name {
                padding: 0 0 0 10px;
            }
        }
    }
</style>