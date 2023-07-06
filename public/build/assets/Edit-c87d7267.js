import{E as k}from"./Error-d79937a0.js";import{S as E}from"./Success-1f5d5948.js";import{P as S}from"./PageTitle-6608e5c7.js";import{_ as w,r as c,o as i,c as n,a as s,b as a,w as u,d as p,g as B,f as d,j as D,k as L,v as x,n as g,F as h,l as f,t as b,u as R}from"./app-18f778da.js";const A={props:["id"],data(){return{msg:"",isError:!1,isLoading:!1,image:"",errors:{},form:{articleTitle:"",articleContent:"",document:null}}},computed:{formData(){let t=new FormData;return t.append("_method","put"),t.append("article_title",this.form.articleTitle),t.append("article_content",this.form.articleContent),this.form.document&&t.append("document",this.form.document),t}},mounted(){this.getArticle()},methods:{setForm(t){this.form={articleTitle:t.articleTitle,articleContent:t.articleContent},this.image=t.document},getArticle(){this.isLoading=!0,this.$store.dispatch("showData",["article",this.id]).then(t=>{this.isLoading=!1,this.setForm(t.data)}).catch(t=>{this.isLoading=!1,t.response.data.status=="ERROR"&&t.response.data.statusCode==500&&(this.isError=!0,this.msg=t.response.data.message)})},handleSubmit(){this.isLoading=!0,this.errors={},this.$store.dispatch("postDataUpload",["article/"+this.id,this.formData]).then(t=>{this.isLoading=!1,$("#successModal").modal("show"),this.msg="data berhasil diperbaharui."}).catch(t=>{this.isLoading=!1,this.errors=t.response.data.messages,t.response.data.status=="ERROR"&&t.response.data.statusCode==500&&(this.isError=!0,this.msg=t.response.data.message)})},uploadThumbnail(t){this.form.document=t.target.files[0]}},components:{PageTitle:S,Success:E,Error:k}},F={class:""},P={class:"breadcrumb m-0"},V={class:"breadcrumb-item"},N={class:"breadcrumb-item"},U=s("li",{class:"breadcrumb-item active"},"Edit Berita",-1),z={class:"card"},M={class:"card-body"},j={class:"mb-2"},O=s("label",null,"Judul Berita",-1),Q=["disabled"],G={class:"mb-2"},J=s("label",null,"Konten Berita",-1),K={class:"mb-2"},q=s("label",null,"Upload Gambar",-1),H=["disabled"],I={key:0,class:"mb-2"},W=["src","alt"],X={class:"card-footer d-flex justify-content-between"},Y=s("button",{class:"btn btn-sm btn-success"},"Simpan",-1);function Z(t,r,tt,et,e,m){const _=c("router-link"),v=c("PageTitle"),y=c("Error"),T=c("QuillEditor"),C=c("Success");return i(),n(h,null,[s("div",F,[a(v,{title:"Edit Berita"},{default:u(()=>[s("ol",P,[s("li",V,[a(_,{to:{name:"Dashboard"}},{default:u(()=>[p("Dashboard")]),_:1})]),s("li",N,[a(_,{to:{name:"Article"}},{default:u(()=>[p("Berita")]),_:1})]),U])]),_:1}),e.isError?(i(),B(y,{key:0,message:e.msg},null,8,["message"])):d("",!0),s("form",{onSubmit:r[3]||(r[3]=D((...o)=>m.handleSubmit&&m.handleSubmit(...o),["prevent"]))},[s("div",z,[s("div",M,[s("div",j,[O,L(s("input",{type:"text",class:g(["form-control",{"is-invalid":e.errors.articleTitle}]),"onUpdate:modelValue":r[0]||(r[0]=o=>e.form.articleTitle=o),disabled:e.isLoading},null,10,Q),[[x,e.form.articleTitle]]),e.errors.articleTitle?(i(!0),n(h,{key:0},f(e.errors.articleTitle,(o,l)=>(i(),n("div",{class:"invalid-feedback",key:l},b(o)+". ",1))),128)):d("",!0)]),s("div",G,[J,a(T,{theme:"snow",toolbar:"full",style:R([{height:"300px"},{borderColor:e.errors.articleContent?"#fa5c7c":""}]),content:e.form.articleContent,"onUpdate:content":r[1]||(r[1]=o=>e.form.articleContent=o),contentType:"html"},null,8,["style","content"]),e.errors.articleContent?(i(!0),n(h,{key:0},f(e.errors.articleContent,(o,l)=>(i(),n("div",{style:{width:"100%","margin-top":"0.25rem","font-size":"0.75rem",color:"#fa5c7c"},key:l},b(o)+". ",1))),128)):d("",!0)]),s("div",K,[q,s("input",{type:"file",class:g(["form-control",{"is-invalid":e.errors.document}]),onChange:r[2]||(r[2]=(...o)=>m.uploadThumbnail&&m.uploadThumbnail(...o)),disabled:e.isLoading},null,42,H),e.errors.document?(i(!0),n(h,{key:0},f(e.errors.document,(o,l)=>(i(),n("div",{class:"invalid-feedback",key:l},b(o)+". ",1))),128)):d("",!0)]),e.image?(i(),n("div",I,[s("img",{src:e.image.documentPath,alt:e.form.articleTitle,class:"img-fluid",height:"250"},null,8,W)])):d("",!0)]),s("div",X,[a(_,{to:{name:"Article"},class:"btn btn-sm btn-secondary"},{default:u(()=>[p("Batal")]),_:1}),Y])])],32)]),a(C,{url:{name:"Article"},msg:e.msg},null,8,["msg"])],64)}const nt=w(A,[["render",Z]]);export{nt as default};