import{_ as c,o as r,c as i,a as e,t as d,y as n,z as _}from"./app-5009bbee.js";const m={data(){return{timer:10}},watch:{timer:{handler(t){t>0&&setTimeout(()=>{this.timer--},1e3),t==0&&(localStorage.removeItem("isPayment"),this.$router.push({name:"Dashboard"}))},immediate:!0}},methods:{redirect(){localStorage.removeItem("isPayment"),this.$router.push({name:"Dashboard"})}}},l=t=>(n("data-v-df461319"),t=t(),_(),t),h={class:"d-flex justify-content-center"},p={class:"card",style:{width:"25rem","margin-top":"5rem"}},u={class:"card-body"},f=l(()=>e("div",{class:"text-center pt-3 pb-3",id:"sent"},[e("span",{class:"dots"},[e("i",{class:"mdi mdi-check-underline"})]),e("h4",{class:"mt-2"},"Pembayaran Berhasil")],-1)),v={class:"text-center mb-2"},y={class:"text-center"};function b(t,s,x,S,a,o){return r(),i("div",h,[e("div",p,[e("div",u,[f,e("div",v,d(a.timer)+" detik lagi, kembali ke Dashboard ",1),e("div",y,[e("a",{href:"#",onClick:s[0]||(s[0]=k=>o.redirect())},"Kembali")])])])])}const I=c(m,[["render",b],["__scopeId","data-v-df461319"]]);export{I as default};
