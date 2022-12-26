<template>
    <div class="install">
        <div class="install-inner">
            <div
                class="install-step"
                v-if="!start">
                <div class="logo">
                    COLLAB
                </div>
                <span class="description">
                    {{ $t('Your personal space for collaboration') }}
                </span>
                <div class="switcher">
                    <LanguageSwitcher @on-locale-change="onLocaleChange" />
                    <ClbButton
                        @click="toStep(1)"
                        class="btn-green">
                        {{ $t('Begin install') }}
                    </ClbButton>
                </div>
            </div>
            <div
                v-else
                class="next-steps">
                <div class="logo">
                    COLLAB
                </div>
                <div class="progress-inner">
                    <ClbProgressbar :width="percents" />
                </div>
                <div class="next-steps-wrapper">
                    {{ heading }}
                    <div
                        class="install-step step"
                        v-if="step === 1">
                        <div class="actions">
                            <ClbButton @click="toStep(0)">
                                {{ $t('Back') }}
                            </ClbButton>
                            <ClbButton
                                class="btn-green"
                                @click="toStep(step +1)">
                                {{ $t('Next') }}
                            </ClbButton>
                        </div>
                    </div>
                    <div
                        class="install-step step"
                        v-if="step ===2">
                        <div class="actions">
                            <ClbButton @click="toStep(step - 1)">
                                {{ $t('Back') }}
                            </ClbButton>
                            <ClbButton
                                class="btn-green"
                                @click="toStep(step + 1)">
                                {{ $t('Next') }}
                            </ClbButton>
                        </div>
                    </div>
                    <div
                        class="install-step step"
                        v-if="step ===3">
                        <div class="actions">
                            <ClbButton @click="toStep(step - 1)">
                                {{ $t('Back') }}
                            </ClbButton>
                            <ClbButton
                                class="btn-green"
                                @click="toStep(step + 1)">
                                {{ $t('Next') }}
                            </ClbButton>
                        </div>
                    </div>
                    <div
                        class="install-step step"
                        v-if="step === 4">
                        <div class="actions">
                            <ClbButton @click="toStep(step - 1)">
                                {{ $t('Back') }}
                            </ClbButton>
                            <ClbButton
                                class="btn-green"
                                @click="toStep(step + 1)">
                                {{ $t('Next') }}
                            </ClbButton>
                        </div>
                    </div>
                    <div
                        class="install-step step"
                        v-if="step === 5">
                        <div class="actions">
                            Done
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ClbProgressbar from '../elements/ClbProgressbar.vue'
import ClbButton from '../elements/ClbButton.vue'
import LanguageSwitcher from '../i18n/LanguageSwitcher.vue'
import axios from 'axios'
export default {
	name: 'Install',
	components: {
		ClbButton,
		ClbProgressbar,
		LanguageSwitcher
	},
	data(){
		return {
			heading: '',
			start: false,
			step: 0,
			percents: 0,
			totalSteps: 5
		}
	},
	watch: {
		step(){
			switch (this.step){
				case 1:
					this.heading = this.$t('Check PHP extensions')
					break
				case 2:
					this.heading = this.$t('Connect to database')
					break
				case 3:
					this.heading = this.$t('Create administrator')
					break
				case 4:
					this.heading = this.$t('Installation')
					break
				case 5:
					this.heading = this.$t('Installation complete')
					break
			}
			this.percents = Math.ceil(100 * (this.step - 1) / (this.totalSteps - 1))
		},
		percents(){
			if(this.percents < 0){
				this.percents = 0
			} else if (this.percents > 100) {
				return 100
			}
		}
	},

	methods: {
		onLocaleChange(locale){
			console.log(`Locale changed to ${locale}`)
		},
		toStep(step){
			if (step <= this.totalSteps && step > 0){
				this.step = step
				this.start = true
			}
			else if(step === 0) {
				this.step = step
				this.start = false
			}
		},
		checkExtentions(){
			setTimeout(()=>{
				this.toStep(this.step + 1)
			},1000)
		}
	}
}
</script>

<style lang="scss" scoped>
	::v-deep(.multiselect-dropdown) {
		border-left: 1px solid var(--color-green);
		border-bottom: 1px solid var(--color-green);
		border-right: 1px solid var(--color-green);
	}
	.install {
		position: fixed;
		top:0;
		left:0;
		right: 0;
		bottom:0;
		z-index: 2;
		background-color: var(--color-green-light);
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		flex-wrap: wrap;
		
		.install-inner {
			width: 430px;
			
			.logo {
				color:var(--color-white);
				font-size: 54px;
				font-weight: bolder;
				text-align: center;
				margin-bottom: 16px;
			}
			
			.description {
				font-size: 14px;
				display: block;
				text-align: center;
				margin-bottom: 30px;
				margin-top: 14px;
				color:var(--color-white)
			}
			
			.switcher {
				max-width: 260px;
				margin: 0 auto;
				
				.btn {
					margin-top: 10px;
					width: 100%;
				}
			}
		}
		
		.next-steps {
			height: 90vh;
			position: relative;
			
			.progress-inner {
				position: relative;
				top:14px;
				padding: 4px 6px;
				z-index: 3;
			}
			
			.next-steps-wrapper {
				background: var(--color-white-opacity90);
				padding: 26px;
				border-radius: var(--border-radius);
				max-height: 660px;
				height: 100%;
				position: relative;
				
				.actions {
					position: absolute;
					left: 8px;
					right: 8px;
					bottom: 8px;
					display: flex;
					align-items: center;
					justify-content: space-between;
				}
			}
		}
	}
</style>