<template>
    <div
        v-if="shouldPaginate"
        class="w-full mt-12 d-flex flex-row justify-between"
    >
        <div class="d-flex flex-row text-lg justify-around">
            <div
                v-if="hasPreviousPage"
                class="mx-2"
            >
                <a
                    class="hover:font-medium cursor-pointer text-sm text-black pointer"
                    rel="prev"
                    @click.prevent="previous"
                >
                    &leftarrow; Предыдущая
                </a>
            </div>
            <div
                v-if="hasNextPage"
                class="mx-2"
            >
                <a
                    class="hover:font-medium cursor-pointer text-sm text-black"
                    rel="next"
                    @click.prevent="next"
                >
                    Следующая &rightarrow;
                </a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'pagination',
    props: {
        response: {
            type: Object,
            default: null,
        },
    },
    data(){
        return{
            page: 10,
        };
    },
    computed: {
        shouldPaginate() {
            return !!this.hasPreviousPage || !!this.hasNextPage;
        },
        hasNextPage() {
            if (this.response !== null){
                if(this.response.data.next_page_url !== undefined)
                    return this.response.data.next_page_url !== null;
                return this.response.next_page_url !== null;
            }
            return false;
        },
        hasPreviousPage() {
            if (this.response !== null){
                if(this.response.data.prev_page_url !== undefined)
                    return this.response.data.prev_page_url !== null;
                return this.response.prev_page_url !== null;
            }
            return false;
        },
        pages() {
            if(this.response.meta !== undefined) {
                let leftOffset = this.response.meta.current_page - 3,
                    rightOffset = this.response.meta.current_page + 3 + 1,
                    range = [];
                for (let i = 1; i <= this.response.meta.last_page; i++) {
                    if (i >= leftOffset && i < rightOffset) {
                        range.push(i);
                    }
                }
                return range;
            }
            let leftOffset = this.response.current_page - 3,
                rightOffset = this.response.current_page + 3 + 1,
                range = [];
            for (let i = 1; i <= this.response.last_page; i++) {
                if (i >= leftOffset && i < rightOffset) {
                    range.push(i);
                }
            }
            return range;
        },
    },
    watch: {
        page: {
            handler: 'load',
        },
    },
    methods: {
        previous() {
            this.page -= 10;
        },
        next() {
            this.page += 10;
        },
        load() {
            this.$emit('load', this.page);
        },
    },
};
</script>
