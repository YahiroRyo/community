<template>
    <div>
        <div :style="`background-color: ${formBackgroundColor};`" :class="{'form-wapper': true, 'form__textarea-wapper': useTextArea,}">
            <textarea  v-if="useTextArea" :value="inputContent" @input="$emit('update:inputContent', $event.target.value)" :style="validate ? 'color: #f9446a;' : ''" class="form__input form__textarea" :type="type" required></textarea>
            <input v-else :value="inputContent" @input="$emit('update:inputContent', $event.target.value)" :style="validate ? 'color: #f9446a;' : ''" class="form__input" :type="type" required>
            <div :class="`form__underline form__underline${uniqueClassKey}`"></div>
            <label :style="validate ? 'color: #f9446a;' : ''" class="form__label">{{label}}</label>
            <p v-if="validate" class="form-error-text">{{error}}</p>
        </div>
        <component is="style">
            .form__underline{{uniqueClassKey}} {
                {{validate ? 'background: #f9446a;' : 'background: silver;' }}
            }
            .form__underline{{uniqueClassKey}}:before {
                {{validate ? 'background: #f9446a;' : 'background: #0BB3A2;;' }}
            }
        </component>
    </div>
</template>

<style>
    .form-wapper {
        height: 40px;
        position: relative;
        width: 100%;
        margin: 0 auto;
        transition: .3s;
    }
    .form__input {
        height: 100%;
        width: 100%;
        font-size: 16px;
        border-bottom: 2px solid silver;
    }
    .form__input:focus ~ .form__label,
    .form__input:valid ~ .form__label {
        transform: translateY(-20px);
        font-size: 15px;
        color: #0BB3A2;
    }
    .form__label {
        position: absolute;
        bottom: 10px;
        left: 0;
        color: grey;
        pointer-events: none;
        transition: all .3s ease;
    }
    .form__input:focus ~ .form__label,
    .form__input:valid ~ .form__label {
        transform: translateY(-25px);
        font-size: 15px;
        color: #0BB3A2;
    }
    .form__textarea,
    .form__textarea-wapper {height: 300px;}
    .form__textarea { resize: none; }

    .form__textarea:focus ~ .form__label,
    .form__textarea:valid ~ .form__label {
        transform: translateY(-295px);
        font-size: 15px;
        color: #0BB3A2;
    }
    .form__underline{
        position: absolute;
        height: 2px;
        width: 100%;
        bottom: 0;
    }
    .form__underline:before{
        position: absolute;
        content: "";
        height: 100%;
        width: 100%;
        transform: scaleX(0);
        transform-origin: center;
        transition: transform .3s ease;
    }
    .form__input:focus ~ .form__underline:before,
    .form__input:valid ~ .form__underline:before{
        transform: scaleX(1);
    }
    .form-error-text {
        color: #f9446a;
        font-size: 14px;
        font-weight: bold;
        margin-top: 5px;
    }
</style>

<script>
    import { watch } from 'vue'

    export default {
        props: {
            label: {
                type:       String,
                required:   true,
            },
            type: {
                default:    'text',
                type:       String,
            },
            formBackgroundColor: {
                default:    '#fff',
                type:       String,
            },
            validate:   {
                default:    false,
                type:       Boolean,
            },
            uniqueClassKey: {
                type:       String,
                required:   true,
            },
            useTextArea: {
                type:       Boolean,
                default:    false,
            },
            error: { type: String, },
            inputContent:   String,
        },
    }
</script>