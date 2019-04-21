import edu_proto from '../../edu/edu';

const edu = new edu_proto();
// 全局环境使用
window.edu = edu;


//示例代码
// setTimeout(() => {
//
//     edu.confirm({ type: 'danger', dataType: 'html', message: '<img src>', title: '321', callback: function (props) {
//             if(props.type === 'success') {
//                 alert('确认了');
//             }else {
//                 alert('取消了');
//             }
//         } });

//     edu.alert('success', '321');
//     edu.alert('success', '321');
// }, 3000);
