import { useI18n } from 'vue3-i18n'

export const $t = (...args) => {
  const { t } = useI18n()
  return t(...args)
}
