import * as themes from '../../themes';

export default class Theme {
    static load(theme,props) {
		return new themes[theme];
    }
}
