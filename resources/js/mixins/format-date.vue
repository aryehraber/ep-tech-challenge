<script>
export default {
    methods: {
        formatDate(date, options) {
            options = options || { dateStyle: 'full' }

            return (new Date(date)).toLocaleDateString('en-GB', options)
        },
        formatTime(date, options) {
            options = options || { timeStyle: 'short' }

            return (new Date(date)).toLocaleTimeString('en-GB', options)
        },
    }
}
</script>
