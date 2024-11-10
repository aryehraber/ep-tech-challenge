<template>
    <div>
        <h1 class="mb-6">Clients -> {{ client.name }} -> {{ formatDate(journal.date, { dateStyle: 'short' }) }}</h1>

        <a :href="`/clients/${client.id}`" class="btn btn-primary btn-sm mb-6">
            < Back to Client
        </a>

        <div class="bg-white rounded p-4">
            <div class="whitespace-pre-wrap">{{ journal.text }}</div>
        </div>
    </div>
</template>

<script>
import FormatDate from '../mixins/format-date.vue';

export default {
    name: 'JournalShow',

    mixins: [FormatDate],

    props: ['client', 'journal'],
}
</script>
