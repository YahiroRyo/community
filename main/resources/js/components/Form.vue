<template>
    <div>
        <div :style="`background-color: $***REMOVED***formBackgroundColor***REMOVED***;`" :class="***REMOVED***'form-wapper': true, 'form__textarea-wapper': useTextArea,***REMOVED***">
            <textarea  v-if="useTextArea" :value="inputContent" @input="$emit('update:inputContent', $event.target.value)" :style="validate ? '' : 'color: #f9446a;'" class="form__input form__textarea" :type="type" required></textarea>
            <input v-else :value="inputContent" @input="$emit('update:inputContent', $event.target.value)" :style="validate ? '' : 'color: #f9446a;'" class="form__input" :type="type" required>
            <div :class="`form__underline form__underline$***REMOVED***uniqueClassKey***REMOVED***`"></div>
            <label :style="validate ? '' : 'color: #f9446a;'" class="form__label">***REMOVED******REMOVED***label***REMOVED******REMOVED***</label>
        </div>
        <component is="style">
            .form__underline***REMOVED******REMOVED***uniqueClassKey***REMOVED******REMOVED*** ***REMOVED***
                ***REMOVED******REMOVED***validate ? 'background: silver;' : 'background: #f9446a;' ***REMOVED******REMOVED***
            ***REMOVED***
            .form__underline***REMOVED******REMOVED***uniqueClassKey***REMOVED******REMOVED***:before ***REMOVED***
                ***REMOVED******REMOVED***validate ? 'background: #0BB3A2;' : 'background: #f9446a;' ***REMOVED******REMOVED***
            ***REMOVED***
        </component>
    </div>
</template>

<style>
    .form-wapper ***REMOVED***
        height: 40px;
        position: relative;
        width: 100%;
        margin: 0 auto;
        transition: .3s;
    ***REMOVED***
    .form__input ***REMOVED***
        height: 100%;
        width: 100%;
        font-size: 16px;
        border-bottom: 2px solid silver;
    ***REMOVED***
    .form__input:focus ~ .form__label,
    .form__input:valid ~ .form__label ***REMOVED***
        transform: translateY(-20px);
        font-size: 15px;
        color: #0BB3A2;
    ***REMOVED***
    .form__label ***REMOVED***
        position: absolute;
        bottom: 10px;
        left: 0;
        color: grey;
        pointer-events: none;
        transition: all .3s ease;
    ***REMOVED***
    .form__input:focus ~ .form__label,
    .form__input:valid ~ .form__label ***REMOVED***
        transform: translateY(-25px);
        font-size: 15px;
        color: #0BB3A2;
    ***REMOVED***
    .form__textarea,
    .form__textarea-wapper ***REMOVED***height: 300px;***REMOVED***
    .form__textarea ***REMOVED*** resize: none; ***REMOVED***

    .form__textarea:focus ~ .form__label,
    .form__textarea:valid ~ .form__label ***REMOVED***
        transform: translateY(-295px);
        font-size: 15px;
        color: #0BB3A2;
    ***REMOVED***
    .form__underline***REMOVED***
        position: absolute;
        height: 2px;
        width: 100%;
        bottom: 0;
    ***REMOVED***
    .form__underline:before***REMOVED***
        position: absolute;
        content: "";
        height: 100%;
        width: 100%;
        transform: scaleX(0);
        transform-origin: center;
        transition: transform .3s ease;
    ***REMOVED***
    .form__input:focus ~ .form__underline:before,
    .form__input:valid ~ .form__underline:before***REMOVED***
        transform: scaleX(1);
    ***REMOVED***
</style>

<script>
    import ***REMOVED*** watch ***REMOVED*** from 'vue'

    export default ***REMOVED***
        props: ***REMOVED***
            label: ***REMOVED***
                type:       String,
                required:   true,
            ***REMOVED***,
            type: ***REMOVED***
                default:    'text',
                type:       String,
            ***REMOVED***,
            formBackgroundColor: ***REMOVED***
                default:    '#fff',
                type:       String,
            ***REMOVED***,
            validate:   ***REMOVED***
                default:    true,
                type:       Boolean,
            ***REMOVED***,
            uniqueClassKey: ***REMOVED***
                type:       String,
                required:   true,
            ***REMOVED***,
            useTextArea: ***REMOVED***
                type:       Boolean,
                default:    false,
            ***REMOVED***,
            inputContent:   String,
        ***REMOVED***,
    ***REMOVED***
</script>