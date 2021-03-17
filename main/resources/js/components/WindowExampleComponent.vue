<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">currentComponent 0</div>
                    <div class="card-header">Window Example Component</div>
                    <div class="card-header">Width 1000 height 500</div>
                    <div class="card-body" style="font-size: 50px;">
                        I'm an example component.
                        I'm an example component.
                        I'm an example component.
                        I'm an example component.
                        I'm an example component.
                        I'm an example component.
                        I'm an example component.
                        I'm an example component.
                        I'm an example component.
                        I'm an example component.
                        I'm an example component.
                        I'm an example component.
                        I'm an example component.
                        I'm an example component.
                        I'm an example component.
                        I'm an example component.
                        I'm an example component.
                        I'm an example component.
                        I'm an example component.
                        I'm an example component.
                        I'm an example component.
                        I'm an example component.
                        I'm an example component.
                        I'm an example component.
                        I'm an example component.
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import ***REMOVED*** setOpenFunction, setCloseFunction, createWindow, window ***REMOVED*** from '../window'

    export default ***REMOVED***
        mounted() ***REMOVED***
            createWindow(new window(0, 'WindowExampleComponent', 1000, 500))
            setOpenFunction(() => ***REMOVED***
                console.log("open")
            ***REMOVED***)
            setCloseFunction(() => ***REMOVED***
                console.log("close")
            ***REMOVED***)
        ***REMOVED***
    ***REMOVED***
</script>
