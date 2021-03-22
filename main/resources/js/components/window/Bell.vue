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
    import ***REMOVED*** reactive, onMounted ***REMOVED*** from 'vue'
    import ***REMOVED*** setOpenFunction, setCloseFunction, createWindow ***REMOVED*** from '../../window'

    export default ***REMOVED***
        setup() ***REMOVED***
            const data = reactive(***REMOVED***

            ***REMOVED***)
            onMounted(() => ***REMOVED***
                createWindow('通知', 1000, 750)
            ***REMOVED***)
            return ***REMOVED*** data ***REMOVED***
        ***REMOVED***
    ***REMOVED***
</script>