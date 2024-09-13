<template>
    {{ text }}
</template>

<script>
export default {
    data() {
        return {
            text: 'Loading...'
        }
    },
    async mounted() {
        try {
            await axios.post('/get-token', {
                code: this.$route.query.code,
            })
            this.text = 'Succcess.'
            localStorage.setItem('zoho', JSON.stringify({
                connected: true
            }))
            location.href = '/'
        } catch (err) {
            this.text = 'Error. Try again.'
        }
    }
}
</script>