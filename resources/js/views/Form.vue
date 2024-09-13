<template>

    <Modal 
        :open="true"
        :mask="false"
        :closable="false"
        :footer="null">

        <Flex 
            v-if="connected"
            :vertical="true"
            :gap="25">

            <Form layout="vertical">

                <FormItem
                    label="Account Name"
                    :required="true"
                    has-feedback
                    :validate-status="errors['account.Account_Name'] ? 'error' : ''"
                    :help="errors['account.Account_Name']">
                    <Input
                        placeholder="Enter Account Name"
                        v-model:value="data.account.Account_Name"/>
                </FormItem>

                <FormItem
                    label="Website"
                    :required="true"
                    has-feedback
                    :validate-status="errors['account.Website'] ? 'error' : ''"
                    :help="errors['account.Website']">
                    <Input
                        placeholder="Enter Website"
                        v-model:value="data.account.Website"/>
                </FormItem>

                <FormItem
                    label="Phone"
                    :required="true"
                    has-feedback
                    :validate-status="errors['account.Phone'] ? 'error' : ''"
                    :help="errors['account.Phone']"
                    @input="formatPhone">
                    <Input
                        placeholder="Enter Phone"
                        v-model:value="data.account.Phone"/>
                </FormItem>

                <FormItem
                    label="Deal Name"
                    :required="true"
                    has-feedback
                    :validate-status="errors['deal.Deal_Name'] ? 'error' : ''"
                    :help="errors['deal.Deal_Name']">
                    <Input
                        placeholder="Enter Deal Name"
                        v-model:value="data.deal.Deal_Name"/>
                </FormItem>

                <FormItem
                    label="Deal Stage"
                    :required="true"
                    has-feedback
                    :validate-status="errors['deal.Stage'] ? 'error' : ''"
                    :help="errors['deal.Stage']">
                    <Select
                        placeholder="Select Deal Stage"
                        :options="stageOptions"
                        v-model:value="data.deal.Stage"/>
                </FormItem>

                <Button
                    :loading="loading"
                    @click="send">
                    Send
                </Button>
                
            </Form>

        </Flex>

        <Flex
            v-if="!connected" 
            :justify="'center'">
            <Button @click="redirect">
                Connect to Zoho
            </Button>
        </Flex>

    </Modal>

</template>

<script>
import { Modal, Form, FormItem, Input, Button, message, Flex, Select, } from 'ant-design-vue'

export default {
    components: {
        Modal, Form, FormItem, 
        Input, Button, Flex, 
        Select,
    },
    data() {
        return {
            data: {
                account: {
                    Account_Name: '',
                    Website: '',
                    Phone: '',
                },
                deal: {
                    Deal_Name: '',
                    Stage: null,
                },
            },
            stages: [
                'Qualification',
                'Needs Analysis',
                'Value Proposition',
                'Identify Decision Makers',
                'Proposal/Price Quote',
                'Negotiation/Review',
                'Closed Won',
                'Closed Lost',
                'Closed-Lost to Competition',
            ],
            errors: {},
            loading: false,
        }
    },
    computed: {
        stageOptions() {
            return this.stages.map(stage => {
                return {
                    value: stage,
                }
            })
        },
        connected() {
            const zoho = JSON.parse(localStorage.getItem('zoho'))

            return zoho?.connected ?? false
        },
    },
    methods: {
        redirect() {
            const url = `${zoho.domain}/oauth/v2/auth`

            const query = new URLSearchParams({
                client_id: zoho.client_id,
                response_type: 'code',
                redirect_uri: zoho.redirect_uri,
                scope: zoho.scope,
                access_type: 'offline',
            })

            return location.href = `${url}?${query}`
        },
        formatPhone() {
            const phone = this.data.account.Phone
                .replace(/\D/g, '')
                .match(/(\d{0,3})(\d{0,3})(\d{0,4})/)
            this.data.account.Phone =
                !phone[2] ? 
                phone[1] : 
                '(' + phone[1] + ') ' + phone[2] + (phone[3] ? '-' + phone[3] : '')
        },
        async send() {
            try {
                this.loading = true
                await axios.post('/handle-form', this.data)
                message.success('Success')
                Object.assign(this.$data, this.$options.data())
            } catch (err) {
                if (err?.response?.status == 422) {
                    this.errors = err?.response?.data?.errors
                } else {
                    message.error(err?.response?.data?.message ?? err.message)
                }
            } finally {
                this.loading = false
            }
        },
    },
}
</script>