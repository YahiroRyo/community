<template>
    <div>
        <link rel="stylesheet" href="/css/components/contact/contact.css">
        <div class="form__wapper">
            <h1 class="contact__title">お問い合わせ</h1>
            <div class="contact__flex">
                <Form class="form contact__input" label="姓" uniqueClassKey="1" />
                <Form class="form contact__input" label="名" uniqueClassKey="2" />
            </div>
            <div class="contact__flex">
                <Form class="form contact__input" label="セイ" uniqueClassKey="3" />
                <Form class="form contact__input" label="メイ" uniqueClassKey="4" />
            </div>
            <Form class="form contact__textarea" :useTextArea="true" label="お問い合わせ内容" uniqueClassKey="5" />
            <button class="form__btn">送信</button>
        </div>
    </div>
</template>

<script>
    /* ---------------コンポーネントをインポート--------------- */
    import Form                                         from '../Form.vue'

    export default {
        components: {
            Form
        },
    }
</script>