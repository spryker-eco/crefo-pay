import './crefopay-form-loader.scss';
import register from 'ShopUi/app/registry';
export default register('crefopay-form-loader', () => import(/* webpackMode: "lazy" */'./crefopay-form-loader'));
