<template>
    <SystemLogComponent/>
</template>

<script>
    import SystemLog from '../../components/audit-trail/SystemLog';

    export default {
        name: 'SystemLog',
        components: { 'SystemLogComponent': SystemLog }
    }
</script>
