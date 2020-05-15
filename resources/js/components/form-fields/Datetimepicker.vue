<template>
    <div class="form-group">
        <!-- Field -->
        <v-text-field 
            :id="name" 
            type="text" 
            :hint="description" 
            :label="title" 
            persistent-hint 
            autocomplete="off" 
            class="form-control" 
            :value="mutableValue" 
            :hide-details="!hasDescription" 
            placeholder="00-00-0000">
        </v-text-field>
        <!-- Hidden input for value -->
        <input type="hidden" :name="name" v-model="mutableValue">
    </div>
</template>

<script>
    export default {
        props: [
            "name",
            "title",
            "value",
            "description",
            "options"
        ],
        data(){
            return {
                tag: "[datepicker-input]",
                mutableValue: this.value
            }
        },
        methods: {
            initialize(){
                $.fn.datepicker.language['nl'] = {
                    days: ['Zondag', 'Maandag', 'Dinsdag', 'Woensdag', 'Donderdag', 'Vrijdag', 'Zaterdag'],
                    daysShort: ['Zo', 'Ma', 'Di', 'Wo', 'Do', 'Vr', 'Za'],
                    daysMin: ['Zo', 'Ma', 'Di', 'Wo', 'Do', 'Vr', 'Za'],
                    months: ['Januari','Februari','Maart','April','Mei','Juni', 'Juli','Augustus','September','Oktober','November','December'],
                    monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec'],
                    today: 'Vandaag',
                    clear: 'Reset',
                    dateFormat: 'mm-dd-yyyy',
                    timeFormat: 'hh:ii',
                    firstDay: 0
                };
                $(document).ready(function(){
                    var dp = $("#"+this.name).datepicker({
                        language: "nl",
                        dateFormat: "dd-mm-yyyy",
                        timepicker: true,
                        autoClose: true,
                        onSelect: function(formattedDate, date, inst) {
                            this.mutableValue = formattedDate;
                        }.bind(this)
                    }).data('datepicker');
                    if (this.value != "" && this.value != null) {
                        let initialDate = new Date(this.value);
                        dp.selectDate(initialDate);
                    }
                }.bind(this));

            },
            hasDescription(){
                return this.description !== false && this.description !== "" ? true : false;
            }
        },
        mounted(){
            this.initialize();
        }
    }
</script>

<style lang="scss">

</style>
