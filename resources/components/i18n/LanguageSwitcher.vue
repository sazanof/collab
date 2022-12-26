<template>
    <ClbMultiselect
        v-model="$i18n.locale"
        :options="locales"
        :can-clear="false"
        @change="changeLocale" />
</template>

<script>
// TODO save locale in store and LocalStorage or DB
import ClbMultiselect from '../elements/ClbMultiselect.vue'

export default {
	name: 'LocaleSwitcher',
	components: {
		ClbMultiselect
	},
	data() {
		return {
			locales: [
				'ru',
				'en'
			]
		}
	},
	computed: {
		currentLocale() {
			return this.$i18n.locale
		}
	},
	methods: {
		changeLocale(){
			this.$emit('on-locale-change', this.currentLocale)
		}
	}
}
</script>

<style lang="scss" scoped>

</style>