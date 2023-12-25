import { useI18n } from 'vue3-i18n'
let t

export const $t = (...args) => {
  t = t || useI18n()?.t
  return t(...args)
}
