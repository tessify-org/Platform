<template>
    <div id="record-overview" :class="{ 'in-tab': inTab }">

        <!-- Header -->
        <div id="record-overview__header" v-if="paginatedRecords.length > 0 && (hasTitle || !hideSearch)">
            <div id="record-overview__header-left">

                <!-- Title -->
                <div id="record-overview__header-title" v-if="hasTitle">{{ title }}</div>

                <!-- Search bar -->
                <div id="record-overview__header-search" v-if="!hideSearch && !searchRight">
                    <v-text-field solo label="Search" v-model="filters.searchQuery" placeholder="Looking for something?" hide-details></v-text-field>
                </div>

            </div>
            <div id="record-overview__header-right">
                
                <!-- Search bar -->
                <div id="record-overview__header-search" v-if="!hideSearch && searchRight">
                    <v-text-field label="Search" v-model="filters.searchQuery" placeholder="Looking for something?" hide-details></v-text-field>
                </div>

                <!-- Create button -->
                <div id="record-overview__header-actions" v-if="hasActions">
                    <v-btn color="primary" :href="createButtonHref">
                        <i class="fas fa-plus"></i>
                        {{ createButtonText }}
                    </v-btn>
                </div>

            </div>
        </div>

        <!-- Records -->
        <div id="record-overview__records" :class="{ 'elevation-1': !noShadow && !inTab }" v-if="paginatedRecords.length > 0">
            <div class="record-overview__record record-header">
                <div class="record-overview__record-col" v-for="(field, fi) in fields" :key="fi" :class="{ centered: columnCentered(field) }">
                    {{ field['label'] }}
                </div>
            </div>
            <a class="record-overview__record" v-for="(record, ri) in paginatedRecords" :key="ri" :href="record.view_href">
                <span class="record-overview__record-col" v-for="(field, fi) in fields" :key="fi" :class="{ centered: columnCentered(field) }">
                    {{ getFieldValue(record, field) }}
                </span>
            </a>
        </div>

        <!-- No records -->
        <div id="record-overview__no-records" :class="{ 'elevation-1': !noShadow && !inTab }" v-if="paginatedRecords.length === 0">
            {{ noRecordText }}
        </div>
        
        <!-- Pagination -->
        <div id="record-overview__pagination" v-if="paginated && numPaginatedPages > 1">
            <v-pagination total-visible="9" v-model="pagination.currentPage" :length="numPaginatedPages"></v-pagination>
        </div>

    </div>
</template>

<script>
    export default {
        props: {
            "fields": Array,
            "records": Array,
            "paginated": {
                type: Boolean,
                default: false
            },
            "paginatedPerPage": {
                type: [Number, String],
                default: 10
            },
            "hideSearch": {
                type: Boolean,
                default: false,
            },
            "searchRight": {
                type: Boolean,
                default: false,
            },
            "title": {
                type: String,
                default: null,
            },
            "noRecordText": {
                type: String,
                default: "No records were found",
            },
            "noShadow": {
                type: Boolean,
                default: false,
            },
            "inTab": {
                type: Boolean,
                default: false,
            },
            "createButtonText": {
                type: String,
                default: null,
            },
            "createButtonHref": {
                type: String,
                default: null
            },
            "locale": {
                type: String,
                default: "nl"
            },
        },
        data: () => ({
            tag: "[record-overview]",
            mutableRecords: [],
            filteredRecords: [],
            filters: {
                searchQuery: "",
            },
            paginatedRecords: [],
            pagination: {
                perPage: 10,
                currentPage: 1
            }
        }),
        computed: {
            numPaginatedPages() {
                return Math.ceil(this.mutableRecords.length / this.pagination.perPage);
            },
            hasTitle() {
                return this.title !== null;
            },
            hasActions() {
                return this.createButtonHref !== null && this.createButtonText !== null;
            },
        },
        watch: {
            "pagination.currentPage": function() {
                this.paginateRecords();
            },
            "filters.searchQuery": function() {
                this.filterRecords();
            },
        },
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" fields: ", this.fields);
                console.log(this.tag+" records: ", this.records);
                console.log(this.tag+" paginated: ", this.paginated);
                console.log(this.tag+" paginated per page: ", this.paginatedPerPage);
                this.initializeData();
            },
            initializeData() {
                // Initialize the records per page parameter
                this.pagination.perPage = this.paginatedPerPage;
                // Initialize the records by placing them in the mutable records array
                if (this.records.length > 0) {
                    for (let i = 0; i < this.records.length; i++) {
                        this.mutableRecords.push(this.records[i]);
                    }
                }
                // Start filtering the records
                this.filterRecords();
            },
            filterRecords() {
                // Reset the current filtered records
                this.filteredRecords = [];
                // Filter the records
                if (this.mutableRecords.length > 0) {
                    for (let i = 0; i < this.mutableRecords.length; i++) {
                        let record = this.mutableRecords[i];
                        if (this.filters.searchQuery !== "") {
                            let matches = false;
                            for (let key in record) {
                                if (!record.hasOwnProperty(key) || record[key] === null) continue;
                                if (record[key].toString().includes(this.filters.searchQuery)) {
                                    matches = true;
                                    break;
                                }
                            }
                            if (!matches) continue;
                        }
                        this.filteredRecords.push(record);
                    }
                }
                // Paginate the filtered records
                this.paginateRecords();
            },
            paginateRecords() {
                if (this.paginated) {
                    let start_at = (this.pagination.currentPage - 1) * this.pagination.perPage;
                    let stop_at = start_at + this.pagination.perPage;
                    this.paginatedRecords = this.filteredRecords.slice(start_at, stop_at);
                    if (this.pagination.currentPage > this.numPaginatedPages) {
                        this.pagination.currentPage = 1;
                    }
                } else {
                    this.paginatedRecords = this.filteredRecords;
                }
            },
            getFieldValue(record, fieldData) {
                // If the record contains the field we're trying to get the value of
                if (record.hasOwnProperty(fieldData["field"]) && record[fieldData["field"]] !== null) {
                    // Retrieve the field's value from the record
                    let value = record[fieldData["field"]];
                    // if (Array.isArray(value)) {
                    //     value = value[this.locale];
                    // }
                    // Type = boolean, return yes or no based on the value
                    if (fieldData.hasOwnProperty("type") && fieldData["type"] === "boolean") {
                        return value === true ? "Yes" : "No";
                    }
                    // Type = capitalized, return the capitalized version of the value
                    if (fieldData.hasOwnProperty("type") && fieldData["type"] === "capitalized") {
                        return value.toString().toUpperCase();
                    }
                    // Return the value in any other case
                    return value;
                }
                return "-";
            },
            columnCentered(field) {
                console.log("centered check: ", field, field.flags);
                if (field.hasOwnProperty("flags") && field.flags.length > 0) {
                    for (let i = 0; i < field.flags.length; i++) {
                        if (field.flags[i] === "centered") return true;
                    }
                }  
                return false;
            },
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    #record-overview {
        margin: 0 0 35px 0;
        &.in-tab {
            margin: 0;
            #record-overview__no-records {
                padding: 0;
                text-align: left;
                background-color: transparent;
            }
            #record-overview__records {
                margin: 0 -30px -30px -30px;
            }
        }
        #record-overview__header {
            display: flex;
            margin: 0 0 25px 0;
            flex-direction: row;
            box-sizing: border-box;
            #record-overview__header-left, #record-overview__header-right {
                display: flex;
                flex-direction: row;
                align-items: center;
                #record-overview__header-search {
                    width: 300px;
                }
            }
            #record-overview__header-left {
                flex: 1;
                #record-overview__header-title {
                    font-size: 16px;
                    font-weight: 600;
                    color: #000000;
                    padding-top: 2px;
                    margin-bottom: -2px;
                    text-transform: uppercase;
                }
            }
            #record-overview__header-right {
                justify-content: flex-end;
            }
        }
        #record-overview__records {
            overflow: hidden;
            border-radius: 3px;
            background-color: hsl(0, 0%, 100%);
            .record-overview__record {
                display: flex;
                padding: 10px 0;
                flex-direction: row;
                align-items: center;
                transition: all .3s;
                text-decoration: none;
                box-sizing: border-box;
                color: hsl(0, 0%, 35%);
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
                &.record-header {
                    font-weight: 500;
                    border-bottom: 2px solid rgba(0, 0, 0, 0.1);
                }
                &:last-child {
                    border-bottom: 0;
                }
                &:hover {
                    color: hsl(0, 0%, 0%);
                    background-color: hsl(0, 0%, 98%);
                }
                .record-overview__record-col {
                    flex: 1;
                    display: flex;
                    padding: 0 15px;
                    flex-direction: row;
                    align-items: center;
                    box-sizing: border-box;
                    &.centered {
                        justify-content: center;   
                    }
                    &:last-child {
                        justify-content: flex-end;
                    }
                    &:first-child {
                        justify-content: flex-start;
                    }
                }
            }
        }
        #record-overview__no-records {
            color: #000000;
            padding: 25px 15px;
            text-align: center;
            border-radius: 3px;
            box-sizing: border-box;
            background-color: hsl(0, 0%, 100%);
        }
        #record-overview__pagination {
            display: flex;
            padding: 15px;
            flex-direction: row;
            align-items: center;
            box-sizing: border-box;
            justify-content: center;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
        }
    }
</style>