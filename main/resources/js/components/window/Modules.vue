<template>
    <p>モジュール一覧です</p>
    <button class="btn">class: btn</button>
    <button class="btn btn_item">class: btn btn_item</button>
    <button class="btn btn_normal">class: btn btn_normal</button>
    <button class="btn btn_success">class: btn btn_success</button>
    <button class="btn btn_attention">class: btn btn_attention</button>
    <button class="btn btn_danger">class: btn btn_danger</button>
    <p>class: btn btn_circle</p>
    <button class="btn btn_circle">B</button>
    <p>class: btn btn_item btn_circle</p>
    <button class="btn btn_item btn_circle">B</button>
    <div class="alert alert_success" style="position: static;">class: alert alert_success</div>
    <div class="alert alert_attention" style="position: static;">class: alert alert_attention</div>
    <div class="alert alert_danger" style="position: static;">class: alert alert_danger</div>
    <div class="window" style="position: static; transform: translate(0, 0); width: 95%; height: 200px; margin-bottom: 20px;">
        <img class="window__close-btn" src="/images/materials/close.svg">
        <h1 class="window__title">windowTitle</h1>
        <div class="window__content">コンポーネント</div>
    </div>
    <label class="form-label">form-label</label>
    <input class="form">
    <textarea class="form form_dont-resize">form form_dont-resize</textarea>
    <button disabled class="form form_btn">form form_btn</button>
</template>

<script>
    import ***REMOVED*** setOpenFunction, setCloseFunction, createWindow ***REMOVED*** from '../../window'

    export default ***REMOVED***
        mounted() ***REMOVED*** createWindow('Modules', 1000, 500) ***REMOVED***
    ***REMOVED***
</script>
