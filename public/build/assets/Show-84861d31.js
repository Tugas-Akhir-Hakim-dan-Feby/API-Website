import{P as h}from"./PageTitle-6608e5c7.js";import{_,r as m,o as c,c as i,b as u,a as t,n as p,t as n,F as v,z as y,d as r}from"./app-18f778da.js";const b={getMessage(s,o,a){window.Echo.channel(s).listen(o,a)},privateMessage(s,o,a){window.Echo.private(s).listen(o,a)}},f={props:["externalId","costId"],data(){return{user:{},payment:{}}},created(){},mounted(){this.getUser(),this.getPayment(),setInterval(()=>{b.privateMessage("App.Models.Payment."+this.externalId,"MessagePayment",s=>{s.message.status==this.$store.state.PAID&&this.$router.push({name:"Invoice Success"})})},1e3)},methods:{getUser(){this.$store.dispatch("showData",["user","me"]).then(s=>{this.user=s.user}).catch(s=>{})},getPayment(){this.$store.dispatch("showData",["payment",this.externalId]).then(s=>{this.payment=s.data}).catch(s=>{})},formatRupiah(s){return new Intl.NumberFormat("id-ID",{style:"currency",currency:"IDR",minimumFractionDigits:0}).format(s??0)},redirect(){this.$router.push({name:"Invoice Success"})}},components:{PageTitle:h}},g={class:"row"},I={class:"col-12"},w={class:"card"},P={class:"card-body"},k={class:"clearfix"},x=t("div",{class:"float-start mb-3"},[t("img",{src:y,alt:"dark logo",height:"75"})],-1),D={class:"float-end"},N={class:"row"},M=t("div",{class:"col-sm-4"},[t("h6",null,"Membayar ke:"),t("address",null,"API-IWS")],-1),B={class:"col-sm-4"},T=t("h6",null,"Ditaggih ke:",-1),A=t("div",{class:"col-sm-4"},[t("div",{class:"text-sm-end"})],-1),S={class:"row"},C={class:"col-12"},F={class:"table-responsive"},R={class:"table mt-4"},V=t("thead",null,[t("tr",null,[t("th",null,"#"),t("th",null,"Deskripsi"),t("th",{class:"text-end"},"Total")])],-1),E=t("td",null,"1",-1),U=t("td",null,[t("b",null,"Laptop"),r(),t("br"),r(' Brand Model VGN-TXN27N/B 11.1" Notebook PC ')],-1),z={class:"text-end"},L={class:"row"},G=t("div",{class:"col-sm-6"},null,-1),W={class:"col-sm-6"},X={class:"float-end mt-3 mt-sm-0"},$=t("div",{class:"clearfix"},null,-1),j={class:"d-print-none mt-4"},q={class:"text-end"},H={key:0,href:"",target:"_blank",class:"btn-sm btn btn-info"},J=["href"];function K(s,o,a,O,e,l){const d=m("PageTitle");return c(),i(v,null,[u(d,{title:"Invoice #"+a.externalId},null,8,["title"]),t("div",g,[t("div",I,[t("div",w,[t("div",P,[t("div",k,[x,t("div",D,[t("h4",{class:p(["m-0 btn btn-sm text-white",e.payment.status==s.$store.state.PAID?"btn-success":"btn-warning"]),style:{cursor:"default"}},n(e.payment.status),3)])]),t("div",N,[M,t("div",B,[T,t("address",null,n(e.user.name),1)]),A]),t("div",S,[t("div",C,[t("div",F,[t("table",R,[V,t("tbody",null,[t("tr",null,[E,U,t("td",z,n(l.formatRupiah(e.payment.amount)),1)])])])])])]),t("div",L,[G,t("div",W,[t("div",X,[t("h3",null,n(l.formatRupiah(e.payment.amount)),1)]),$])]),t("div",j,[t("div",q,[e.payment.status==s.$store.state.PAID?(c(),i("a",H,"Cetak")):(c(),i("a",{key:1,href:e.payment.paymentLink,target:"_blank",class:"btn-sm btn btn-primary"},"Bayar",8,J))])])])])])])],64)}const Z=_(f,[["render",K]]);export{Z as default};
