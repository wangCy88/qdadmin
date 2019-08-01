<template>
    <div class="myNav">
        <div class="left">LanYue</div>
        <div class="center">
            <a href="/">首页</a>
            <a href="/store">商城</a>
            <a href="/find">发现</a>
            <a href="/artists">艺术家</a>
            <a href="/activities">活动</a>
        </div>
        <div class="right">
            <img :src="avatar"/>
        </div>
    </div>
</template>


<script>
    export default {
        data() {
            return {
                avatar: "http://lanyue.ink/images/avatar.jpg",
            };
        },
    };
</script>

<style lang="scss">
    .myNav {
        position: fixed;
        z-index: 10;
        top: 0;
        width: 100%;
        height: 50px;
        background-color: #000;
        color: white;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .left,
    .center,
    .right {
        height: 50px;
        display: flex;
        align-items: center;
    }

    .left {
        margin: 0 50px;
        width: 100px;
    }

    .center {
        a {
            margin: 0 20px;
            text-decoration: none;
            color: white;
        }
    }

    .right {
        width: 100px;
        margin: 0 50px;
        justify-content: flex-end;
        img {
            $a: 30px;
            width: $a;
            height: $a;
            border-radius: 100%;
        }
    }
</style>