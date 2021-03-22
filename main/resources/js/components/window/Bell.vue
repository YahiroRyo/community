<template>
    <div>
        <div class="post">
            <div class="post__flex">
                <p class="post__font"><strong>〇〇さん</strong>が〇〇コミュニティへ入りたいと加入申請をしてきました。</p>
            </div>
        </div>
    </div>
</template>

<script>
    import { reactive, onMounted } from 'vue'
    import { setOpenFunction, setCloseFunction, createWindow } from '../../window'

    export default {
        setup() {
            const data = reactive({

            })
            onMounted(() => {
                createWindow('通知', 1000, 750)
            })
            return { data }
        }
    }
</script>