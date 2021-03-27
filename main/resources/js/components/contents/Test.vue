<template>
    <div>
        <Form uniqueClassKey="1" label="Name" :validate="false" class="test__form" />
        <Form label="mail" uniqueClassKey="2" class="test__form" />
        <Form label="userName" uniqueClassKey="3" class="test__form" />
        <Form label="password" uniqueClassKey="4" type="password" class="test__form" />
    </div>
</template>

<style>
    .test__form {
        padding: 20px;
        background-color: white;
    }
</style>

<script>
    import Form from '../Form.vue'

    export default {
        components: {
            Form,
        },
    }
</script>