/**
 * @Description webpack编译静态资源构造类
 * @Author: zhonghuakc@gmail.com
 * @Date: 2019-2-22
 * @Last Modified by: zhonghuakc@gmail.com
 * @Last Modified time: 2019-2-22
 **/

import glob from 'glob';
import mix from 'laravel-mix';

class multiplentry__proto {
    constructor() {
        this.glob = glob;
        this.mix = mix;
    }

    buildAssets() {
        this.glob.sync(this.path)
            .forEach(item => {

                let outputPathArr = this.Clear(this.path, item).split('/')
                    , output;

                // 不检索以 _ 开头文件；
                if ((/^_/).test(outputPathArr[outputPathArr.length - 1])) {
                    return;
                }

                output = outputPathArr.slice(0, -1).join('/');

                if(this.type === 'css') {
                    mix.sass(item, `public/${this.type}/${output}`);
                }

                if(this.type === 'js') {
                    mix.js(item, `public/${this.type}/${output}`);
                }

            });
    }
}

multiplentry__proto.prototype.Clear = function (str1, str2) {
    let shorter
        , longer
        , String1
        , targetstring;

    if (str1 > str2) {

        shorter = str2;

        longer = str1;

    } else {

        shorter = str1;

        longer = str2;

    }
    for (let a = shorter.length; a > 0; a--) {

        for (let b = 0; a + b < shorter.length; b++) {

            String1 = shorter.substring(b, a + b);

            if (longer.indexOf(String1) >= 0) {

                targetstring = String1;

                return str2.replace(targetstring, '');
            }
        }
    }
};

class multiplentry extends multiplentry__proto {
    constructor(path, type) {
        super();
        this.path = path;
        this.type = type;
    }
}

export default multiplentry;