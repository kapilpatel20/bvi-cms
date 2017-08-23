<?php

namespace BviCmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BviCmsBundle\Entity\Cms;
use BviCmsBundle\Form\CmsForm;
use DateTime;

class CmsController extends Controller {

    public function indexAction(Request $request) {
        
        $lstObj = $this->prepareListObj($request);
        $lstObj->setTemplate('BviCmsBundle:AjaxPagination:ajax_pagination.html.twig');
        
        if ($request->isXmlHttpRequest()) {
            
            $listView          =  $this->renderView('BviCmsBundle:Cms:_list.html.twig',array('lstObj' => $lstObj));
            $output['success'] = true;
            $output['listView']= $listView;
            $response = new Response(json_encode($output));
            return $response;
            
        }else{
            return $this->render('BviCmsBundle:Cms:index.html.twig',array('lstObj' => $lstObj));
        }
        
    }
    
    //prepare list object
    public function prepareListObj(Request $request) {
        
        $em        = $this->getDoctrine()->getManager();
        $params    = $this->get('request')->request->all();
        
        $qry       = $em->getRepository('BviCmsBundle:Cms')->getList($params);

        $itmPerPge = 20;
        // Creating pagnination
        $pagination = $this->get('knp_paginator')->paginate(
                $qry, $this->get('request')->query->get('page', 1), $itmPerPge
        );
        
        return $pagination;
    }
    
    //add cms page
    
    public function newAction(Request $request) {
        
        $objCms = new Cms();
        $form = $this->createForm(new CmsForm(), $objCms);
        
        if ($request->getMethod() == "POST") {

            $form->handleRequest($request);

            if ($form->isValid()) {
                
                $objCms->setSlug();
                $objCms->setCreatedat(new DateTime());
                $user = $this->get('security.context')->getToken()->getUser();
                if (is_object($user)) {
                    $objCms->setCreatedby($user->getId());
                }else{
                    $objCms->setCreatedby(1);
                }
                $em = $this->getDoctrine()->getManager();
                $em->persist($objCms);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', "Record has been added successfully.");
                return $this->redirect($this->generateUrl('bvi_cms_list'));
            }
        }
        
        return $this->render('BviCmsBundle:Cms:new.html.twig', array(
                    'form' => $form->createView()
        ));
    }
    
    //edit cms page
    
    public function editAction(Request $request,$id = '') {
        
        $em = $this->getDoctrine()->getManager();
        $objCms = $em->getRepository('BviCmsBundle:Cms')->find($id);
        
        if (!$objCms) {

            $this->get('session')->getFlashBag()->add('failure', "Cms Page does not exist.");
            return $this->redirect($this->generateUrl('bvi_cms_list'));
        }
        $form = $this->createForm(new CmsForm(), $objCms);

        if ($request->getMethod() == "POST") {

            $form->handleRequest($request);

            if ($form->isValid()) {
                
                $objCms->setSlug();
                $objCms->setUpdatedat(new DateTime());
                $user = $this->get('security.context')->getToken()->getUser();
                if (is_object($user)) {
                    $objCms->setUpdatedby($user->getId());
                }
                $em->persist($objCms);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', "Record has been updated successfully.");
                return $this->redirect($this->generateUrl('bvi_cms_list'));
            }
        }
        return $this->render('BviCmsBundle:Cms:edit.html.twig', array(
                    'form' => $form->createView(),'objCms' => $objCms
        ));
    }
    
    //update status of cms page
    
    public function updateStatusAction(Request $request) {
        
        $em     = $this->getDoctrine()->getManager();
        $id     = $request->get('id');
        $objCms = $em->getRepository('BviCmsBundle:Cms')->find($id);
        $success= false;
        
        if (is_object($objCms)) {
            
            $status = $objCms->getStatus() == 'Active' ? 'Inactive' : 'Active';
            $objCms->setStatus($status);
            $objCms->setUpdatedat(new DateTime());
            $user = $this->get('security.context')->getToken()->getUser();
            if (is_object($user)) {
                $objCms->setUpdatedby($user->getId());
            }
            $em->persist($objCms);
            $em->flush();
            $success = true;
        }
        
        $output['success'] = $success;
        $output['msg']     = 'Record updated successfully';
        $response          = new Response(json_encode($output));
        return $response;
    }    
    
}    
    