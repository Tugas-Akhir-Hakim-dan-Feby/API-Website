import{_ as c,o as a,c as e,a as s}from"./app-18f778da.js";const n={props:["msg","url"],methods:{handleOk(){$("#successModal").modal("hide"),this.$router.push(this.url)}}},i={class:"modal fade",id:"successModal",tabindex:"-1","aria-labelledby":"successModalLabel","aria-hidden":"true","data-bs-backdrop":"static","data-bs-keyboard":"false"},r={class:"modal-dialog modal-dialog-centered"},b={class:"modal-content"},m=s("div",{class:"modal-header"},[s("h5",{class:"modal-title",id:"successModalLabel"},"berhasil")],-1),_=["innerHTML"],u={class:"modal-footer"},h={key:0,type:"button",class:"btn btn-sm btn-primary","data-bs-dismiss":"modal"};function k(p,t,d,y,f,o){return a(),e("div",i,[s("div",r,[s("div",b,[m,s("div",{class:"modal-body",innerHTML:d.msg},null,8,_),s("div",u,[d.url?(a(),e("button",{key:1,onClick:t[0]||(t[0]=(...l)=>o.handleOk&&o.handleOk(...l)),type:"button",class:"btn btn-sm btn-primary"}," OK ")):(a(),e("button",h," OK "))])])])])}const M=c(n,[["render",k]]);export{M as S};