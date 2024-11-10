<template>
    <div>
        <h1 class="mb-6">Clients -> {{ client.name }}</h1>

        <div class="flex">
            <div class="w-1/3 mr-5">
                <div class="w-full bg-white rounded p-4">
                    <h2>Client Info</h2>
                    <table>
                        <tbody>
                            <tr>
                                <th class="text-gray-600 pr-3">Name</th>
                                <td>{{ client.name }}</td>
                            </tr>
                            <tr>
                                <th class="text-gray-600 pr-3">Email</th>
                                <td>{{ client.email }}</td>
                            </tr>
                            <tr>
                                <th class="text-gray-600 pr-3">Phone</th>
                                <td>{{ client.phone }}</td>
                            </tr>
                            <tr>
                                <th class="text-gray-600 pr-3">Address</th>
                                <td>{{ client.address }}<br/>{{ client.postcode }} {{ client.city }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="w-2/3">
                <div>
                    <button class="btn" :class="{'btn-primary': currentTab == 'bookings', 'btn-default': currentTab != 'bookings'}" @click="switchTab('bookings')">Bookings</button>
                    <button class="btn" :class="{'btn-primary': currentTab == 'journals', 'btn-default': currentTab != 'journals'}" @click="switchTab('journals')">Journals</button>
                </div>

                <!-- Bookings -->
                <div class="bg-white rounded p-4" v-if="currentTab == 'bookings'">
                    <div class="flex items-center justify-between mb-3">
                        <h3>List of client bookings</h3>

                        <form>
                            <select
                                v-model="currentBookingType"
                                name="booking_type"
                                class="px-2 py-1 border rounded-sm"
                                @change="(e) => e.target.form.submit()"
                            >
                                <option value="">All bookings</option>
                                <option value="future">Future bookings only</option>
                                <option value="past">Past bookings only</option>
                            </select>
                        </form>
                    </div>

                    <template v-if="bookingList && bookingList.length > 0">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Notes</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="booking in bookingList" :key="booking.id">
                                    <td>
                                        {{ formatDate(booking.start) }},
                                        {{ formatTime(booking.start) }} to {{ formatTime(booking.end) }}
                                    </td>
                                    <td>{{ booking.notes }}</td>
                                    <td>
                                        <button class="btn btn-danger btn-sm" @click="deleteBooking(booking)">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </template>

                    <template v-else>
                        <p class="text-center">The client has no bookings.</p>
                    </template>
                </div>

                <!-- Journals -->
                <div class="bg-white rounded p-4" v-if="currentTab == 'journals'">
                    <div class="flex items-center justify-between mb-3">
                        <h3>List of client journals</h3>

                        <a :href="`/clients/${client.id}/journals/create`" class="float-right btn btn-primary">+ New Journal</a>
                    </div>

                    <template v-if="journalList && journalList.length > 0">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Snippet</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="journal in journalList" :key="journal.id">
                                    <td>{{ formatDate(journal.date) }}</td>
                                    <td>
                                      <p>{{ journal.snippet }}</p>
                                    </td>
                                    <td class="flex space-x-1 text-right">
                                        <a class="btn btn-primary btn-sm" :href="`/clients/${client.id}/journals/${journal.id}`">View</a>
                                        <button class="btn btn-danger btn-sm" @click="deleteJournal(journal)">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </template>

                    <template v-else>
                        <p class="text-center">The client has no journals.</p>
                    </template>
                </div>
            </div>
        </div>

        <div class="alert alert-success fixed-top text-center" role="alert" v-if="alertMessage">
            {{ alertMessage }}
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import FormatDate from '../mixins/format-date.vue';

export default {
    name: 'ClientShow',

    mixins: [FormatDate],

    props: ['client', 'bookingType'],

    data() {
        return {
            currentTab: 'bookings',
            currentBookingType: this.bookingType || '',
            bookingList: [],
            journalList: [],
            alertMessage: '',
        }
    },

    methods: {
        switchTab(newTab) {
            this.currentTab = newTab;
        },

        deleteBooking(booking) {
            axios.delete(`/clients/${this.client.id}/bookings/${booking.id}`)
                .then(resp => {
                    if (resp.data.status === 'success') {
                        const index = this.bookingList.findIndex(({ id }) => booking.id === id);

                        if (index !== -1) {
                            this.bookingList.splice(index, 1);
                        }

                        this.alertMessage = resp.data.message;

                        setTimeout(() => this.alertMessage = '', 5000);
                    }
                });
        },

        deleteJournal(journal) {
            axios.delete(`/clients/${this.client.id}/journals/${journal.id}`)
                .then(resp => {
                    if (resp.data.status === 'success') {
                        const index = this.journalList.findIndex(({ id }) => journal.id === id);

                        if (index !== -1) {
                            this.journalList.splice(index, 1);
                        }

                        this.alertMessage = resp.data.message;

                        setTimeout(() => this.alertMessage = '', 5000);
                    }
                });
        },
    },

    mounted() {
        this.bookingList = [...this.client.bookings]
        this.journalList = [...this.client.journals]
    }
}
</script>
