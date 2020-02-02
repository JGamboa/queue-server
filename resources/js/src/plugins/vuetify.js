import Vue from 'vue'
import Vuetify from 'vuetify/lib'


Vue.use(Vuetify);

export default new Vuetify({
    icons: {
        iconfont: 'md',
    },
    theme: {
        themes: {
            light: {
                primary: '#1867C0',
                secondary: '#5CBBF6',
                tertiary: '#E57373',
                accent: '#005CAF',
            },
        },
    },
    options: {
        minifyTheme: css => {
            return process.env.NODE_ENV === 'production'
                ? css.replace(/[\s|\r\n|\r|\n]/g, '')
                : css
        },
    },
})
