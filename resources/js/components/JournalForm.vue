<template>
    <div>
        <h1 class="mb-6">Clients -> {{ client.name }} -> Add New Journal</h1>

        <div class="max-w-lg mx-auto">
            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" id="date" class="form-control" v-model="journal.date">
            </div>
            <div class="form-group">
                <label for="text">Text</label>
                <textarea id="text" class="form-control" rows="10" v-model="journal.text"></textarea>
            </div>

            <div class="alert alert-danger" role="alert" v-if="errors.length">
                <ul class="m-0">
                    <li v-for="error in errors">• {{ error }}</li>
                </ul>
            </div>

            <div class="text-right">
                <a :href="`/clients/${client.id}`" class="btn btn-default">Cancel</a>
                <button @click="storeJournal" class="btn btn-primary">Create</button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'JournalForm',

    props: ['client'],

    data() {
        return {
            journal: {
                date: '',
                text: '',
            },
            errors: [],
        }
    },

    methods: {
        storeJournal() {
            this.errors = []

            axios.post(`/clients/${this.client.id}/journals`, this.journal)
                .then((data) => {
                    window.location.href = data.data.url;
                })
                .catch(err => {
                    const errors = err.response.data.errors

                    if (! errors) return

                    for (const key in errors) {
                        this.errors.push(errors[key][0])
                    }
                });
        }
    }
}
</script>
