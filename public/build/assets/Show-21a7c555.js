import{P as d}from"./PageTitle-7945d5aa.js";import{_ as h,r as _,o as c,c as i,b as m,a as t,n as u,t as o,F as p,B as v}from"./app-5009bbee.js";const y={getMessage(s,a,n){window.Echo.channel(s).listen(a,n)},privateMessage(s,a,n){window.Echo.private(s).listen(a,n)}},f={props:["externalId","costId"],data(){return{user:{},payment:{}}},created(){},mounted(){this.getUser(),this.getPayment(),setInterval(()=>{y.privateMessage("App.Models.Payment."+this.externalId,"MessagePayment",s=>{s.message.status==this.$store.state.PAID&&this.$router.push({name:"Invoice Success"})})},1e3)},methods:{getUser(){this.$store.dispatch("showData",["user","me"]).then(s=>{this.user=s.user}).catch(s=>{})},getPayment(){this.$store.dispatch("showData",["payment",this.externalId]).then(s=>{this.payment=s.data}).catch(s=>{})},formatRupiah(s){return new Intl.NumberFormat("id-ID",{style:"currency",currency:"IDR",minimumFractionDigits:0}).format(s??0)},redirect(){this.$router.push({name:"Invoice Success"})}},components:{PageTitle:d}},g={class:"row"},b={class:"col-12"},I={class:"card"},w={class:"card-body"},x={class:"clearfix"},P=t("div",{class:"float-start mb-3"},[t("img",{src:v,alt:"dark logo",height:"75"})],-1),k={class:"float-end"},D={class:"row"},M=t("div",{class:"col-sm-4"},[t("h6",null,"Membayar ke:"),t("address",null,"API-IWS")],-1),A={class:"col-sm-4"},B=t("h6",null,"Ditaggih ke:",-1),S=t("div",{class:"col-sm-4"},[t("div",{class:"text-sm-end"})],-1),F={class:"row"},R={class:"col-12"},T={class:"table-responsive"},C={class:"table mt-4"},E=t("thead",null,[t("tr",null,[t("th",null,"#"),t("th",null,"Deskripsi"),t("th",{class:"text-end"},"Total")])],-1),N=t("td",null,"1",-1),U={class:"text-capitalize"},z={class:"text-end"},V={class:"row"},L=t("div",{class:"col-sm-6"},null,-1),W={class:"col-sm-6"},j={class:"float-end mt-3 mt-sm-0"},q=t("div",{class:"clearfix"},null,-1),G={class:"d-print-none mt-4"},H={class:"text-end"},J={key:0,href:"",target:"_blank",class:"btn-sm btn btn-info"},K=["href"];function O(s,a,n,Q,e,l){const r=_("PageTitle");return c(),i(p,null,[m(r,{title:"Invoice #"+n.externalId},null,8,["title"]),t("div",g,[t("div",b,[t("div",I,[t("div",w,[t("div",x,[P,t("div",k,[t("h4",{class:u(["m-0 btn btn-sm text-white",e.payment.status==s.$store.state.PAID?"btn-success":"btn-warning"]),style:{cursor:"default"}},o(e.payment.status),3)])]),t("div",D,[M,t("div",A,[B,t("address",null,o(e.user.name),1)]),S]),t("div",F,[t("div",R,[t("div",T,[t("table",C,[E,t("tbody",null,[t("tr",null,[N,t("td",null,[t("b",U,o(e.payment.description),1)]),t("td",z,o(l.formatRupiah(e.payment.amount)),1)])])])])])]),t("div",V,[L,t("div",W,[t("div",j,[t("h3",null,o(l.formatRupiah(e.payment.amount)),1)]),q])]),t("div",G,[t("div",H,[e.payment.status==s.$store.state.PAID?(c(),i("a",J,"Cetak")):(c(),i("a",{key:1,href:e.payment.paymentLink,target:"_blank",class:"btn-sm btn btn-primary"},"Bayar",8,K))])])])])])])],64)}const Z=h(f,[["render",O]]);export{Z as default};
