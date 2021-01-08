<?php

namespace PruebaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use PruebaBundle\Entity\User;
use PruebaBundle\Form\UserType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('@Prueba/Default/index.html.twig');
    }
    //esto es un redireccionamieneto, si no escribe santa fe o reconquista te lleva a google
    public function contactarAction($lugar)
    {
            if($lugar=='santafe'){
                //le digo que genere la url y la busque en la ruta prueba_nombre2
                return $this->redirect($this->generateUrl('prueba_nombre2'));
            }elseif ($lugar=='Reconquista'){
                return $this->render('@Prueba/Default/index.html.twig');
            }else{
                //realiza una excepcion
                throw $this->createNotFoundException('Estas equivocade');
            }

    }
    
    public function nombreAction($nPila)
    {
        return $this->render('@Prueba/Default/nombre.html.twig',array('nPila'=>$nPila));
    }

    public function nombreSinParamAction()
    {
        return $this->render('@Prueba/Default/index.html.twig');
    }

    //te muestra el nombre que recibe como parametro, esta concatenado mediantes los .
    public function nombreRAction($nPila)
    {
        return new Response('<html><head><body>Hola '.$nPila.'</body></head></html>');
    }

    public function nombresAction(){
        $nombres = [
            "Lucia"=> ["nombre"=>"Lucia",
                         "activo"=>true],
            "Paco"=>["nombre"=>"Paco",
            "activo"=>false],
            "Luis"=>["nombre"=>"Luis",
            "activo"=>true],
            "Maria"=>["nombre"=>"Maria",
            "activo"=>false]];
        return $this->render('@Prueba/Default/nombres.html.twig', array('nombres'=>$nombres));
    }


    public function registerAction(Request $request)
    {

        // 1) build the form
        $user = new User();
        $form = $this->createForm(UserType::class, $user);


        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password =  $user->getPlainPassword();
            $user->setPassword($password);

            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user


            return new Response("Usuario registrado");
        }

        return $this->render('@Prueba/Default/register.html.twig', ['form' => $form->createView()]);
    }

    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('@Prueba/Default/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }
    public function usuariosAction()
    {
        return $this->render('@Prueba/Default/usuarios.html.twig');
    }
}
