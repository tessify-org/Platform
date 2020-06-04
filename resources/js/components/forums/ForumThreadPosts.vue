<template>
    <div id="forum-thread-posts">
        <div id="forum-thread-posts__list" v-if="mutablePosts.length > 0">
            <div class="forum-thread-post elevation-1" v-for="(post, pi) in mutablePosts" :key="pi">
                <div class="forum-thread-post__content">
                    {{ post.message }}
                </div>
                <div class="forum-thread-post__footer">
                    <div class="forum-thread-post__footer-left">
                        {{ post.user.formatted_name }} {{ strings.on }} {{ post.formatted_created_at }}
                    </div>
                    <div class="forum-thread-post__footer-right" v-if="user.id === post.user_id">
                        <div class="forum-thread-post__action edit" @click="onClickEdit(pi)">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div class="forum-thread-post__action delete" @click="onClickDelete(pi)">
                            <i class="fas fa-trash-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            "user",
            "thread",
            "strings",
        ],
        data: () => ({
            tag: "[forum-thread-posts]",
            mutablePosts: [],
        }),
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" user: ", this.user);
                console.log(this.tag+" threads: ", this.threads);
                console.log(this.tag+" strings: ", this.strings);
                this.initializeData();
            },
            initializeData() {
                if (this.thread !== undefined && this.thread !== null && this.thread.posts !== undefined && this.thread.posts !== null && this.thread.posts.length > 0) {
                    for (let i = 0; i < this.thread.posts.length; i++) {
                        this.mutablePosts.push(this.thread.posts[i]);                        
                    }
                }
            },
            onClickEdit(index) {
                window.location.href = this.mutablePosts[index].update_href;
            },
            onClickDelete(index) {
                window.location.href = this.mutablePosts[index].delete_href;
            },
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    #forum-thread-posts {
        #forum-thread-posts__list {
            .forum-thread-post {
                overflow: hidden;
                border-radius: 3px;
                margin: 0 0 30px 0;
                background-color: #ffffff;
                &:last-child {
                    margin: 0;
                }
                .forum-thread-post__content {
                    padding: 15px;
                    box-sizing: border-box;
                }
                .forum-thread-post__footer {
                    display: flex;
                    font-size: .9em;
                    padding: 10px 15px;
                    flex-direction: row;
                    box-sizing: border-box;
                    background-color: hsl(0, 0%, 98%);
                    .forum-thread-post__footer-left {
                        flex: 1;
                        display: flex;
                        flex-direction: row;
                        align-items: center;
                    }
                    .forum-thread-post__footer-right {
                        flex: 1;
                        display: flex;
                        flex-direction: row;
                        align-items: center;
                        justify-content: flex-end;
                        .forum-thread-post__action {
                            margin: 0 0 0 15px;
                            cursor: pointer;
                            &.edit {
                                color: #fd7200;
                            }
                            &.delete {
                                color: #ff0000;
                            }
                            &:first-child {
                                margin: 0;
                            }
                        }
                    }
                }
            }
        }
    }
</style>