import{P as w}from"./PageTitle-bd1d27ae.js";import{S as C}from"./Success-26290fb4.js";import{_ as P,e as I,r as f,o,c as l,b,a as s,k as p,v as _,n as d,F as c,l as u,t as h,f as a,p as k,w as B,d as g,j as L}from"./app-dfbfb837.js";const x={components:{PageTitle:w,Success:C},data(){return{form:{welderSkillIds:[],residentIdCard:"",dateBirth:"",birthPlace:"",workingStatus:"",documentCertificateSchool:"",documentPasPhoto:"",documentCertificateCompetency:""},welderSkills:[],errors:{},isLoading:!1}},mounted(){this.selectUtil();let t=JSON.parse(localStorage.getItem("isPayment"));t&&this.$router.push({name:"Show Invoice",params:{externalId:t.externalId,costId:"welderMember"}})},computed:{formData(){let t=new FormData;for(let i=0;i<this.form.welderSkillIds.length;i++)t.append(`welder_skill_ids[${i}]`,this.form.welderSkillIds[i]);return t.append("resident_id_card",this.form.residentIdCard),t.append("date_birth",this.form.dateBirth),t.append("birth_place",this.form.birthPlace),t.append("working_status",this.form.workingStatus),t.append("document_certificate_school",this.form.documentCertificateSchool),t.append("document_pas_photo",this.form.documentPasPhoto),t.append("document_certificate_competency",this.form.documentCertificateCompetency),t}},methods:{onBack(){this.$router.push({name:"Member"})},handleSubmit(){this.isLoading=!0,this.errors={},this.$store.dispatch("postDataUpload",["user/welder-member",this.formData]).then(t=>{this.isLoading=!1,localStorage.setItem("isPayment",JSON.stringify({paymentType:"welderMember",externalId:t.data.externalId})),window.location.href="/invoice/"+t.data.externalId+"/welderMember"}).catch(t=>{this.isLoading=!1,this.errors=t.response.data.messages,t.response.data.status=="ERROR"&&t.response.data.statusCode==500&&(this.isError=!0,this.msg=t.response.data.message)})},selectUtil(){$(this.$refs.welderSkill).select2({ajax:{url:`${this.$store.state.BASE_URL}/api/v1/skill/welder`,dataType:"json",headers:{Authorization:"Bearer "+I.get("token")},delay:250,data:function(t){return{search:t.term,per_page:20}},processResults:function(t){return{results:t.data.map(i=>({id:i.uuid,text:i.skill_name}))}},cache:!0}}),$(this.$refs.welderSkill).on("change",()=>{this.form.welderSkillIds=$(this.$refs.welderSkill).val(),$(".select2-hidden-accessible").removeClass("is-invalid")})},uploadCertificateSchool(t){this.form.documentCertificateSchool=t.target.files[0]},uploadPasPhoto(t){this.form.documentPasPhoto=t.target.files[0]},uploadCertificateCompetency(t){this.form.documentCertificateCompetency=t.target.files[0]}}},T={class:"card"},D={class:"card-body"},M={class:"mb-3"},U=s("label",null,"NIK",-1),V=["disabled"],N={class:"mb-3"},R=s("label",null,"Tempat Lahir",-1),j=["disabled"],F={class:"mb-3"},O=s("label",null,"Tanggal Lahir",-1),E=["disabled"],K={class:"mb-3"},J=s("label",null,"Status Bekerja",-1),z={class:"form-check"},A=["disabled"],W=s("label",{class:"form-check-label"}," Ya ",-1),H={class:"form-check"},Y=["disabled"],q=s("label",{class:"form-check-label"}," Tidak ",-1),G={class:"mb-3"},Q=s("label",null,"Jenis Kompetensi",-1),X=["disabled"],Z={class:"mb-3"},ee=s("label",null,"Pas Foto Formal Berwarna",-1),te=["disabled"],se={class:"mb-3"},re=s("label",null,"Sertifikat Pelatihan/Kompetensi Keahlian Pengelasan ",-1),ie=["disabled"],oe={class:"card-footer border-top d-flex justify-content-between"},le={key:0,class:"btn btn-sm btn-primary"},ne={key:1,class:"btn btn-sm btn-primary",type:"button",disabled:""},ae=s("span",{class:"spinner-border spinner-border-sm me-1",role:"status","aria-hidden":"true"},null,-1);function de(t,i,ce,me,e,m){const v=f("PageTitle"),y=f("router-link"),S=f("Success");return o(),l(c,null,[b(v,{title:"Daftar Member Welder",isBack:!0,onOnBack:m.onBack},null,8,["onOnBack"]),s("form",{onSubmit:i[7]||(i[7]=L((...r)=>m.handleSubmit&&m.handleSubmit(...r),["prevent"])),method:"post"},[s("div",T,[s("div",D,[s("div",M,[U,p(s("input",{type:"text",class:d(["form-control",{"is-invalid":e.errors.residentIdCard}]),"onUpdate:modelValue":i[0]||(i[0]=r=>e.form.residentIdCard=r),disabled:e.isLoading},null,10,V),[[_,e.form.residentIdCard]]),e.errors.residentIdCard?(o(!0),l(c,{key:0},u(e.errors.residentIdCard,(r,n)=>(o(),l("div",{class:"invalid-feedback",key:n},h(r),1))),128)):a("",!0)]),s("div",N,[R,p(s("input",{type:"text",class:d(["form-control",{"is-invalid":e.errors.birthPlace}]),"onUpdate:modelValue":i[1]||(i[1]=r=>e.form.birthPlace=r),disabled:e.isLoading},null,10,j),[[_,e.form.birthPlace]]),e.errors.birthPlace?(o(!0),l(c,{key:0},u(e.errors.birthPlace,(r,n)=>(o(),l("div",{class:"invalid-feedback",key:n},h(r),1))),128)):a("",!0)]),s("div",F,[O,p(s("input",{type:"date",class:d(["form-control",{"is-invalid":e.errors.dateBirth}]),"onUpdate:modelValue":i[2]||(i[2]=r=>e.form.dateBirth=r),disabled:e.isLoading},null,10,E),[[_,e.form.dateBirth]]),e.errors.dateBirth?(o(!0),l(c,{key:0},u(e.errors.dateBirth,(r,n)=>(o(),l("div",{class:"invalid-feedback",key:n},h(r),1))),128)):a("",!0)]),s("div",K,[J,s("div",z,[p(s("input",{class:d(["form-check-input",{"is-invalid":e.errors.workingStatus}]),type:"radio",value:"1","onUpdate:modelValue":i[3]||(i[3]=r=>e.form.workingStatus=r),disabled:e.isLoading},null,10,A),[[k,e.form.workingStatus]]),W]),s("div",H,[p(s("input",{class:d(["form-check-input",{"is-invalid":e.errors.workingStatus}]),type:"radio",value:"0","onUpdate:modelValue":i[4]||(i[4]=r=>e.form.workingStatus=r),disabled:e.isLoading},null,10,Y),[[k,e.form.workingStatus]]),q,e.errors.workingStatus?(o(!0),l(c,{key:0},u(e.errors.workingStatus,(r,n)=>(o(),l("div",{class:"invalid-feedback",key:n},h(r),1))),128)):a("",!0)])]),s("div",G,[Q,s("select",{class:d(["select2-hidden-accessible",{"is-invalid":e.errors.welderSkillIds}]),ref:"welderSkill",disabled:e.isLoading,multiple:""},null,10,X),e.errors.welderSkillIds?(o(!0),l(c,{key:0},u(e.errors.welderSkillIds,(r,n)=>(o(),l("div",{class:"invalid-feedback",key:n},h(r),1))),128)):a("",!0)]),s("div",Z,[ee,s("input",{type:"file",class:d(["form-control",{"is-invalid":e.errors.documentPasPhoto}]),disabled:e.isLoading,onChange:i[5]||(i[5]=(...r)=>m.uploadPasPhoto&&m.uploadPasPhoto(...r))},null,42,te),e.errors.documentPasPhoto?(o(!0),l(c,{key:0},u(e.errors.documentPasPhoto,(r,n)=>(o(),l("div",{class:"invalid-feedback",key:n},h(r),1))),128)):a("",!0)]),s("div",se,[re,s("input",{type:"file",class:d(["form-control",{"is-invalid":e.errors.documentCertificateCompetency}]),disabled:e.isLoading,onChange:i[6]||(i[6]=(...r)=>m.uploadCertificateCompetency&&m.uploadCertificateCompetency(...r))},null,42,ie),e.errors.documentCertificateCompetency?(o(!0),l(c,{key:0},u(e.errors.documentCertificateCompetency,(r,n)=>(o(),l("div",{class:"invalid-feedback",key:n},h(r),1))),128)):a("",!0)])]),s("div",oe,[b(y,{to:{name:"Member"},class:"btn btn-sm btn-secondary",disabled:e.isLoading},{default:B(()=>[g("Batal")]),_:1},8,["disabled"]),e.isLoading?a("",!0):(o(),l("button",le," Simpan ")),e.isLoading?(o(),l("button",ne,[ae,g(" Harap Tunggu... ")])):a("",!0)])])],32),b(S,{url:{name:"Dashboard"},msg:"data berhasil diregistrasi."},null,8,["msg"])],64)}const fe=P(x,[["render",de]]);export{fe as default};
