<template>
    <div>
        <button @click="anim" class="form__btn">
            test
        </button>
    </div>
</template>

<style>
    .test__form ***REMOVED***
        padding: 20px;
        background-color: white;
    ***REMOVED***
</style>

<script>
    import Form from '../Form.vue'

    export default ***REMOVED***
        components: ***REMOVED***
            Form,
        ***REMOVED***,
        setup() ***REMOVED***
            const btnAnim = (e) => ***REMOVED***
                let x = e.clientX - e.target.offsetLeft
                let y = e.clientY - e.target.offsetTop

                let ripples = document.createElement('span')
                ripples.style.left = x + 'px'
                ripples.style.top = y + 'px'
                e.target.appendChild(ripples)
                setTimeout(() => ***REMOVED***
                    ripples.remove()
                ***REMOVED***, 100000)
            ***REMOVED***
            const anim = (e) => ***REMOVED***
            ***REMOVED***
        ***REMOVED***
    ***REMOVED***
</script>