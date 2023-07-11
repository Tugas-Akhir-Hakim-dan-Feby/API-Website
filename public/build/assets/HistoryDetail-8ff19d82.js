import{P as y}from"./PageTitle-7945d5aa.js";import{L as v}from"./Loader-adc66e3a.js";import{_ as g,r as l,o as r,c as _,b as c,w as d,a as t,g as k,f as h,n as P,t as n,F as w,B as x,d as u}from"./app-5009bbee.js";const D={props:["externalId"],data(){return{user:{},payment:{},isLoading:!1}},created(){},mounted(){this.getUser(),this.getPayment()},methods:{getUser(){this.$store.dispatch("showData",["user","me"]).then(s=>{this.user=s.user}).catch(s=>{})},getPayment(){this.isLoading=!0,this.$store.dispatch("showData",["payment",this.externalId]).then(s=>{this.isLoading=!1,this.payment=s.data}).catch(s=>{this.isLoading=!1})},handlePrint(){window.location.href=`/print/invoice/${this.externalId}`},onBack(s){this.$router.push({name:"Payment History"})},formatRupiah(s){return new Intl.NumberFormat("id-ID",{style:"currency",currency:"IDR",minimumFractionDigits:0}).format(s??0)}},components:{PageTitle:y,Loader:v}},I={class:"breadcrumb m-0"},B={class:"breadcrumb-item"},L={class:"breadcrumb-item"},C=t("li",{class:"breadcrumb-item active"},"Detail Pembayaran",-1),N={class:"row"},R={class:"col-12"},T={class:"card position-relative"},F={class:"card-body"},V={class:"clearfix"},A=t("div",{class:"float-start mb-3"},[t("img",{src:x,alt:"dark logo",height:"75"})],-1),H={class:"float-end"},S={class:"row"},U=t("div",{class:"col-sm-4"},[t("h6",null,"Membayar ke:"),t("address",null,"API-IWS")],-1),z={class:"col-sm-4"},E=t("h6",null,"Ditaggih ke:",-1),M=t("div",{class:"col-sm-4"},[t("div",{class:"text-sm-end"})],-1),O={class:"row"},W={class:"col-12"},j={class:"table-responsive"},q={class:"table mt-4"},G=t("thead",null,[t("tr",null,[t("th",null,"#"),t("th",null,"Deskripsi"),t("th",{class:"text-end"},"Total")])],-1),J=t("td",null,"1",-1),K=t("br",null,null,-1),Q={class:"text-end"},X={class:"row"},Y=t("div",{class:"col-sm-6"},null,-1),Z={class:"col-sm-6"},$={class:"float-end mt-3 mt-sm-0"},tt=t("div",{class:"clearfix"},null,-1),st={class:"d-print-none mt-4"},et={class:"text-end"};function ot(s,o,p,at,e,a){const m=l("router-link"),b=l("PageTitle"),f=l("Loader");return r(),_(w,null,[c(b,{title:"Invoice #"+p.externalId,isBack:!0,onOnBack:o[0]||(o[0]=i=>a.onBack(i))},{default:d(()=>[t("ol",I,[t("li",B,[c(m,{to:{name:"Dashboard"}},{default:d(()=>[u("Dashboard")]),_:1})]),t("li",L,[c(m,{to:{name:"Payment History"}},{default:d(()=>[u("Riwayat Pembayaran")]),_:1})]),C])]),_:1},8,["title"]),t("div",N,[t("div",R,[t("div",T,[e.isLoading?(r(),k(f,{key:0})):h("",!0),t("div",F,[t("div",V,[A,t("div",H,[t("h4",{class:P(["m-0 btn text-white btn-sm",e.payment.status==s.$store.state.PAID?"btn-success":"btn-warning"]),style:{cursor:"default"}},n(e.payment.status),3)])]),t("div",S,[U,t("div",z,[E,t("address",null,n(e.user.name),1)]),M]),t("div",O,[t("div",W,[t("div",j,[t("table",q,[G,t("tbody",null,[t("tr",null,[J,t("td",null,[t("b",null,n(e.payment.description),1),K]),t("td",Q,n(a.formatRupiah(e.payment.amount)),1)])])])])])]),t("div",X,[Y,t("div",Z,[t("div",$,[t("h3",null,n(a.formatRupiah(e.payment.amount)),1)]),tt])]),t("div",st,[t("div",et,[e.payment.status==s.$store.state.PAID?(r(),_("button",{key:0,onClick:o[1]||(o[1]=(...i)=>a.handlePrint&&a.handlePrint(...i)),class:"btn btn-info btn-sm"}," Cetak ")):h("",!0)])])])])])])],64)}const rt=g(D,[["render",ot]]);export{rt as default};
