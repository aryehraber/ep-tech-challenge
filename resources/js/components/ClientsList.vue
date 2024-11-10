<template>
    <div>
        <h1>
            Clients
            <a href="/clients/create" class="float-right btn btn-primary">+ New Client</a>
        </h1>

        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Number of Bookings</th>
                    <th>Number of Journals</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="client in clientList" :key="client.id">
                    <td>{{ client.name }}</td>
                    <td>{{ client.email }}</td>
                    <td>{{ client.phone }}</td>
                    <td>{{ client.bookings_count }}</td>
                    <td>{{ client.journals_count }}</td>
                    <td>
                        <a class="btn btn-primary btn-sm" :href="`/clients/${client.id}`">View</a>
                        <button class="btn btn-danger btn-sm" @click="deleteClient(client)">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="alert alert-success fixed-top text-center" role="alert" v-if="alertMessage">
            {{ alertMessage }}
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'ClientsList',

    props: ['clients'],

    data() {
        return {
            clientList: [],
            alertMessage: '',
        }
    },

    methods: {
        deleteClient(client) {
            axios.delete(`/clients/${client.id}`)
                .then(resp => {
                    if (resp.data.status === 'success') {
                        const index = this.clientList.findIndex(({ id }) => client.id === id);

                        if (index !== -1) {
                            this.clientList.splice(index, 1);
                        }

                        this.alertMessage = resp.data.message;

                        setTimeout(() => this.alertMessage = '', 5000);
                    }
                });
        }
    },

    mounted() {
        this.clientList = [...this.clients]
    }
}
</script>
