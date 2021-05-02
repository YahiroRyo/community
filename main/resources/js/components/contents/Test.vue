<template>
    <div>
        <button @click="anim" class="form__btn">
            test
        </button>
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
        setup() {
            const btnAnim = (e) => {
                let x = e.clientX - e.target.offsetLeft
                let y = e.clientY - e.target.offsetTop

                let ripples = document.createElement('span')
                ripples.style.left = x + 'px'
                ripples.style.top = y + 'px'
                e.target.appendChild(ripples)
                setTimeout(() => {
                    ripples.remove()
                }, 100000)
            }
            const anim = (e) => {
            }
        }
    }
</script>