<template>
    <div>
        <template v-for="(post, key) in data.post.objects" :key="key">
            <Post
                :name="post.name"
                :userName="post.userName" 
                :content="post.content"
                :goodNum="post.goodNum"
                :responceNum="post.responceNum"
                :sendGood="sendGood"
                :sendGoodKey="key"
                :isGood="post.isGood"
            />
        </template>
    </div>
</template>

<script>
    import ***REMOVED*** reactive, onMounted ***REMOVED*** from 'vue'
    import ***REMOVED*** post ***REMOVED*** from '../../post.js'
    import Post from '../Post.vue'

    export default ***REMOVED***
        components: ***REMOVED*** Post, ***REMOVED***,
        setup() ***REMOVED***
            const data = reactive(***REMOVED***
                post: ***REMOVED***
                    objects: [],
                ***REMOVED***
            ***REMOVED***)
            const sendGood = (key) => ***REMOVED***
                data.post.objects[key].isGood = !data.post.objects[key].isGood
                data.post.objects[key].isGood ? data.post.objects[key].goodNum++ : data.post.objects[key].goodNum--
            ***REMOVED***
            onMounted(() => ***REMOVED***
                /* ---------------TODO: サーバーから投稿内容を取得するajax処理を実装--------------- */

                // 仮で表示するために値を格納
                for (let i = 0; i < 100; i++)
                    data.post.objects.push(new post('name', 'userName', 'content', true, 1, 5))
            ***REMOVED***)
            return ***REMOVED*** data, sendGood ***REMOVED***
        ***REMOVED***
    ***REMOVED***
</script>