<template>
    <div id="memberlist__wrapper">

        <!-- Filters -->
        <div id="filters">
            <div id="filters-left">
                <!-- Search -->
                <v-text-field solo v-model="filters.searchQuery" hide-details :placeholder="searchText"></v-text-field>
            </div>
            <div id="filters-right">
                <!-- Ministry -->
                <div class="filter">
                    <v-combobox
                        solo
                        hide-details
                        :placeholder="ministryText"
                        v-model="filters.ministry"
                        :items="ministryOptions">
                    </v-combobox>
                </div>
                <!-- Organization -->
                <div class="filter">
                    <v-combobox
                        solo
                        hide-details
                        v-model="filters.organization"
                        :placeholder="organizationText"
                        :items="organizationOptions">
                    </v-combobox>
                </div>
                <!-- Reset -->
                <div class="filter">
                    <v-btn class="icon-only" @click="onClickResetFilters">
                        <i class="fas fa-sync"></i>
                    </v-btn>
                </div>
            </div>
        </div>

        <!-- Memberlist -->
        <div id="memberlist" class="elevation-1" v-if="paginatedUsers.length > 0">

            <!-- Headings -->
            <div id="memberlist-headings">
                <div class="memberlist-heading">
                    {{ nameText }}
                </div>
                <div class="memberlist-heading">
                    {{ reputationText }}
                </div>
            </div>

            <!-- Records -->
            <a class="memberlist-record" v-for="(user, ui) in paginatedUsers" :key="ui" :href="user.profile_href">
                <div class="record-name">
                    {{ user.formatted_name }}
                </div>
                <div class="record-reputation">
                    {{ user.reputation_points+" "+pointsText }}
                </div>
            </a>

        </div>

        <!-- No users found -->
        <div id="no-users" class="elevation-1" v-if="paginatedUsers.length === 0">
            {{ noRecordsText }}
        </div>

        <!-- Pagination -->
        <div id="pagination" v-if="numPaginatedPages > 1">
            <v-pagination
                v-model="pagination.currentPage"
                :length="numPaginatedPages"
                total-visible="10">
            </v-pagination>
        </div>

    </div>
</template>

<script>
    export default {
        props: [
            "users",
            "ministries",
            "organizations",
            "nameText",
            "reputationText",
            "noRecordsText",
            "pointsText",
            "searchText",
            "ministryText",
            "organizationText",
        ],
        data: () => ({
            tag: "[memberlist]",
            organizationOptions: [],
            ministryOptions: [],
            mutableUsers: [],
            filters: {
                searchQuery: "",
                ministry: "",
                organization: "",
            },
            filteredUsers: [],
            pagination: {
                perPage: 10,
                currentPage: 1,
            },
            paginatedUsers: [],
        }),
        computed: {
            numPaginatedPages() {
                return Math.ceil(this.filteredUsers.length/this.pagination.perPage);
            },
        },
        watch: {
            "pagination.currentPage": function() {
                this.paginateUsers();
            },
            "filters": {
                deep: true,
                handler: function() {
                    this.filterUsers();
                }
            },
            "filters.ministry": function() {
                this.generateOrganizationOptions();
            },
        },
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" users: ", this.users);
                console.log(this.tag+" ministries: ", this.ministries);
                console.log(this.tag+" organizations: ", this.organizations);
                console.log(this.tag+" name text: ", this.nameText);
                console.log(this.tag+" reputation text: ", this.reputationText);
                console.log(this.tag+" no records text: ", this.noRecordsText);
                console.log(this.tag+" points text: ", this.pointsText);
                console.log(this.tag+" search text: ", this.searchText);
                // console.log(this.tag+" ");
                this.generateOrganizationOptions();
                this.generateMinistryOptions();
                this.initializeData();
            },
            generateOrganizationOptions() {
                this.organizationOptions = [];
                for (let i = 0; i < this.organizations.length; i++) {
                    if (this.filters.ministry !== "") {
                        let ministry = this.getMinistryByName(this.filters.ministry);
                        if (ministry && this.organizations[i].ministry_id === ministry.id) {
                            this.organizationOptions.push(this.organizations[i].name);
                        }
                    } else {
                        this.organizationOptions.push(this.organizations[i].name);
                    }
                }
            },
            generateMinistryOptions() {
                for (let i = 0; i < this.ministries.length; i++) {
                    this.ministryOptions.push(this.ministries[i].name);
                }
            },
            initializeData() {
                if (this.users !== undefined && this.users !== null && this.users.length > 0) {
                    for (let i = 0; i < this.users.length; i++) {
                        this.mutableUsers.push(this.users[i]);
                    }
                }
                this.filterUsers();
            },
            filterUsers() {
                this.filteredUsers = [];
                for (let i = 0; i < this.mutableUsers.length; i++) {
                    let user = this.mutableUsers[i];
                    // Filter on search query
                    if (this.filters.searchQuery !== "") {
                        if (!user.formatted_name.toLowerCase().includes(this.filters.searchQuery.toLowerCase())) {
                            continue;
                        }
                    }
                    // Filter on ministry
                    if (this.filters.ministry !== "" && this.filters.ministry !== undefined && this.filters.ministry !== null) {
                        let ministry = this.getMinistryByName(this.filters.ministry);
                        let currentAssignment = this.getUsersCurrentAssignment(user);
                        if (!ministry || !currentAssignment || currentAssignment.organization.ministry_id !== ministry.id) {
                            continue;
                        }
                    }
                    // Filter on organization
                    if (this.filters.organization !== "" && this.filters.organization !== undefined && this.filters.organization !== null) {
                        let organization = this.getOrganizationByName(this.filters.organization);
                        let currentAssignment = this.getUsersCurrentAssignment(user);
                        if (!organization || !currentAssignment || currentAssignment.organization_id !== organization.id) {
                            continue;
                        }
                    }
                    // If we've reached this point; add user to list of filtered users
                    this.filteredUsers.push(user);
                }
                this.paginateUsers();
            },
            paginateUsers() {
                let start_slicing_at = (this.pagination.currentPage - 1) * this.pagination.perPage;
                let stop_slicing_at = start_slicing_at + this.pagination.perPage;
                this.paginatedUsers = this.filteredUsers.slice(start_slicing_at, stop_slicing_at);
                if (this.pagination.currentPage > this.numPaginationPages) this.pagination.currentPage = 1;
            },
            getMinistryByName(name) {
                for (let i = 0; i < this.ministries.length; i++) {
                    if (this.ministries[i].name === name) {
                        return this.ministries[i];
                    }
                }
                return false;
            },
            getOrganizationByName(name) {
                for (let i = 0; i < this.organizations.length; i++) {
                    if (this.organizations[i].name === name) {
                        return this.organizations[i];
                    }
                }
                return false;
            },
            getUsersCurrentAssignment(user) {
                for (let i = 0; i < user.assignments.length; i++) {
                    console.log(user.assignments[i]);
                    if (user.assignments[i].current) {
                        return user.assignments[i];
                    }
                }
                return false;
            },
            onClickResetFilters() {
                this.filters.searchQuery = "";
                this.filters.ministry = "";
                this.filters.organization = "";
            },
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    #memberlist__wrapper {
        #filters {
            margin: 0 0 30px 0;
            display: flex;
            flex-direction: row;
            #filters-left {
                display: flex;
                flex: 0 0 300px;
                flex-direction: row;
                align-items: center;
            }
            #filters-right {
                flex: 1;
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: flex-end;
                .filter {
                    margin: 0 0 0 15px;
                    &:first-child {
                        margin: 0;
                    }
                }
            }
        }
        #memberlist {
            border-radius: 3px;
            background-color: #fff;
            #memberlist-headings {
                display: flex;
                padding: 15px 20px;
                flex-direction: row;
                box-sizing: border-box;
                background-color: hsl(0, 0%, 96%);
                .memberlist-heading {
                    flex: 1;
                    display: flex;
                    font-weight: 500;
                    flex-direction: row;
                    align-items: center;
                    &:last-child {
                        justify-content: flex-end;
                    }
                }
            }
            .memberlist-record {
                display: flex;
                padding: 15px 20px;
                flex-direction: row;
                align-items: center;
                transition: all .3s;
                text-decoration: none;
                box-sizing: border-box;
                color: rgba(0, 0, 0, 0.75);
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
                &:last-child {
                    border-bottom: 0;
                }
                &:hover {
                    color: rgba(0, 0, 0, 1);
                }
                .record-name {
                    flex: 1;
                    display: flex;
                    flex-direction: row;
                    align-items: center;
                }
                .record-reputation {
                    flex: 1;
                    display: flex;
                    flex-direction: row;
                    align-items: center;
                    justify-content: flex-end;
                }
            }
        }
        #no-users {
            padding: 25px;
            text-align: center;
            border-radius: 3px;
            box-sizing: border-box;
            background-color: #fff;
        }
        #pagination {
            display: flex;
            margin: 30px 0 0 0;
            flex-direction: row;
            align-items: center;
            justify-content: center;
        }
    }
</style>