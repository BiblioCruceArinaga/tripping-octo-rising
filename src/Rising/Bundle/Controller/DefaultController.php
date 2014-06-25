<?php

    namespace Rising\Bundle\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\Request;
    use Rising\Bundle\Entity\Empleo;

    class DefaultController extends Controller
    {
	public function base_enAction(Request $request)
        { 	    
	    $locale = $request->getLocale();
	    $request->setLocale($locale);
       	    return $this->render('base-rising.html.twig');
        }

	public function index_enAction(Request $request)
        { 	    
	    $locale = $request->getLocale();
	    $request->setLocale($locale);

       	    return $this->render('RisingBundle:Plantillas:index_en.html.twig');
        }

        public function indexAction(Request $request)
        { 	    
	    $locale = $request->getLocale();
	    $request->setLocale($locale);

       	    return $this->render('RisingBundle:Plantillas:index.html.twig');
        }
	
        public function avisolegalAction(Request $request)
        {
	    $locale = $request->getLocale();
	    $request->setLocale($locale);
            return $this->render('RisingBundle:Plantillas:aviso-legal.html.twig');
        }

	public function avisolegal_enAction(Request $request)
        {
	    $locale = $request->getLocale();
	    $request->setLocale($locale);
            return $this->render('RisingBundle:Plantillas:aviso-legal_en.html.twig');
        }

        public function politicaprivacidadAction()
        {
            return $this->render('RisingBundle:Plantillas:politica-privacidad.html.twig');
        }

	public function politicaprivacidad_enAction()
        {
            return $this->render('RisingBundle:Plantillas:politica-privacidad_en.html.twig');
        }

        public function blogAction()
        {
            return $this->redirect('http://rising.es/blog');
        }

        public function nosotrosAction(Request $request)
        {
	    $locale = $request->getLocale();
	    $request->setLocale($locale);
            return $this->render('RisingBundle:Plantillas:nosotros.html.twig');
        }
        public function nosotros_enAction(Request $request)
        {
	    $locale = $request->getLocale();
	    $request->setLocale($locale);
            return $this->render('RisingBundle:Plantillas:nosotros_en.html.twig');
        }

        public function empleoAction(Request $request)
        {
            $errors = array();
            $successes = array();
            $empleo = new Empleo();
            
            $form = $this->createFormBuilder($empleo)
                ->add('curriculo', 'file', array('label' => 'Currículo'))
                ->add('mensaje', 'textarea', array('required' => false))
                ->getForm();

            $form->handleRequest($request);
            
            //  Un usuario nos ha enviado su CV
            if ($form->isValid()) {
                $fichero = $form['curriculo']->getData();
                $mensaje = $form['mensaje']->getData();

                $extension = $fichero->guessExtension();
                if (
                    (strcmp($extension,"doc") == 0) ||
                    (strcmp($extension,"docx") == 0) ||
                    (strcmp($extension,"pdf") == 0) ||
                    (strcmp($extension,"odt") == 0) 
                ) {
                    
                    if ($fichero->getClientSize() <= 1048576) {

                        try {

                            //  Cambiar el nombre al archivo por seguridad
                            $ruta = $this->container->get('kernel')->getRootdir() . "/../web/";
                            $nombre = "fichero." . $extension;

                            $fichero->move($ruta, $nombre);

                            $message = \Swift_Message::newInstance()
                                ->setSubject('Rising - CV enviado')
                                ->setFrom("info@rising.es")
                                ->setTo("info@rising.es")
                                ->setBody("Nos han enviado un CV: " . $mensaje)
                                ->attach(\Swift_Attachment::fromPath($ruta . $nombre))
                            ;

                            //  Recibimos el CV correctamente
                            if ($this->get('mailer')->send($message) !== false) {
				$translated = $this->get('translator')->trans('Hemos recibido tu CV. Contactaremos contigo en breve. Muchas gracias.', array(), 'jobs');        
                                $successes[] = $translated;
                                return $this->render('RisingBundle:Plantillas:empleo.html.twig', 
                                    array('form' => $form->createView(), 
                                            'errors' => $errors, 'successes' => $successes) 
                                );
                            }

                            //  Hubo un error y no recibimos el CV
                            else {
				$translated = $this->get('translator')->trans('No pudimos recibir tu CV. Por favor, inténtalo de nuevo más tarde.', array(), 'jobs');
                                $errors[] = $translated;
                                return $this->render('RisingBundle:Plantillas:empleo.html.twig', 
                                    array('form' => $form->createView(),
                                            'errors' => $errors, 'successes' => $successes) 
                                );
                            }
                        }
                        catch (Exception $e) {
			    $translated = $this->get('translator')->trans('Hubo un error y no pudimos recibir tu CV. Por favor, inténtalo de nuevo más tarde.', array(), 'jobs');
                            $errors[] = $translated;
                            return $this->render('RisingBundle:Plantillas:empleo.html.twig', 
                                array('form' => $form->createView(),
                                        'errors' => $errors, 'successes' => $successes) 
                            );
                        }
                    }

                    //  El fichero es demasiado grande
                    else {
			$translated = $this->get('translator')->trans('Tamaño máximo del fichero: 1 MB', array(), 'jobs');
                        $errors[] = $translated;
                        return $this->render('RisingBundle:Plantillas:empleo.html.twig', 
                            array('form' => $form->createView(),
                                    'errors' => $errors, 'successes' => $successes) 
                        );
                    }
                }
                
                //  No es una extensión de fichero válida
                else {
		    $translated = $this->get('translator')->trans('Formatos de archivo válidos: .doc, .docx, .pdf, .odt.', array(), 'jobs');
                    $errors[] = $translated;
                    return $this->render('RisingBundle:Plantillas:empleo.html.twig', 
                        array('form' => $form->createView(),
                                'errors' => $errors, 'successes' => $successes) 
                    );
                }
            }

            //  Acceso normal al formulario de empleo
            else {
                return $this->render('RisingBundle:Plantillas:empleo.html.twig', 
                    array('form' => $form->createView(),
                            'errors' => $errors, 'successes' => $successes) 
                );
            }
        }

	public function empleo_enAction(Request $request)
        {
	    $locale = $request->getLocale();
	    $request->setLocale($locale);
            $errors = array();
            $successes = array();
            $empleo = new Empleo();
            
            $form = $this->createFormBuilder($empleo)
                ->add('curriculo', 'file', array('label' => 'Currículo'))
                ->add('mensaje', 'textarea', array('required' => false))
                ->getForm();

            $form->handleRequest($request);
            
            //  Un usuario nos ha enviado su CV
            if ($form->isValid()) {
                $fichero = $form['curriculo']->getData();
                $mensaje = $form['mensaje']->getData();

                $extension = $fichero->guessExtension();
                if (
                    (strcmp($extension,"doc") == 0) ||
                    (strcmp($extension,"docx") == 0) ||
                    (strcmp($extension,"pdf") == 0) ||
                    (strcmp($extension,"odt") == 0) 
                ) {
                    
                    if ($fichero->getClientSize() <= 1048576) {

                        try {

                            //  Cambiar el nombre al archivo por seguridad
                            $ruta = $this->container->get('kernel')->getRootdir() . "/../web/";
                            $nombre = "fichero." . $extension;

                            $fichero->move($ruta, $nombre);

                            $message = \Swift_Message::newInstance()
                                ->setSubject('Rising - CV enviado')
                                ->setFrom("info@rising.es")
                                ->setTo("info@rising.es")
                                ->setBody("Nos han enviado un CV: " . $mensaje)
                                ->attach(\Swift_Attachment::fromPath($ruta . $nombre))
                            ;

                            //  Recibimos el CV correctamente
                            if ($this->get('mailer')->send($message) !== false) {
				$translated = $this->get('translator')->trans('Hemos recibido tu CV. Contactaremos contigo en breve. Muchas gracias.', array(), 'jobs');        
                                $successes[] = $translated;
                                return $this->render('RisingBundle:Plantillas:empleo_en.html.twig', 
                                    array('form' => $form->createView(), 
                                            'errors' => $errors, 'successes' => $successes) 
                                );
                            }

                            //  Hubo un error y no recibimos el CV
                            else {
				$translated = $this->get('translator')->trans('No pudimos recibir tu CV. Por favor, inténtalo de nuevo más tarde.', array(), 'jobs');
                                $errors[] = $translated;
                                return $this->render('RisingBundle:Plantillas:empleo_en.html.twig', 
                                    array('form' => $form->createView(),
                                            'errors' => $errors, 'successes' => $successes) 
                                );
                            }
                        }
                        catch (Exception $e) {
			    $translated = $this->get('translator')->trans('Hubo un error y no pudimos recibir tu CV. Por favor, inténtalo de nuevo más tarde.', array(), 'jobs');
                            $errors[] = $translated;
                            return $this->render('RisingBundle:Plantillas:empleo_en.html.twig', 
                                array('form' => $form->createView(),
                                        'errors' => $errors, 'successes' => $successes) 
                            );
                        }
                    }

                    //  El fichero es demasiado grande
                    else {
			$translated = $this->get('translator')->trans('Tamaño máximo del fichero: 1 MB', array(), 'jobs');
                        $errors[] = $translated;
                        return $this->render('RisingBundle:Plantillas:empleo_en.html.twig', 
                            array('form' => $form->createView(),
                                    'errors' => $errors, 'successes' => $successes) 
                        );
                    }
                }
                
                //  No es una extensión de fichero válida
                else {
		    $translated = $this->get('translator')->trans('Formatos de archivo válidos: .doc, .docx, .pdf, .odt.', array(), 'jobs');
                    $errors[] = $translated;
                    return $this->render('RisingBundle:Plantillas:empleo_en.html.twig', 
                        array('form' => $form->createView(),
                                'errors' => $errors, 'successes' => $successes) 
                    );
                }
            }

            //  Acceso normal al formulario de empleo
            else {
                return $this->render('RisingBundle:Plantillas:empleo_en.html.twig', 
                    array('form' => $form->createView(),
                            'errors' => $errors, 'successes' => $successes) 
                );
            }
        }

        public function contactoAction(Request $request)
        {
            $name = $request->request->get('name',"");
            $email = $request->request->get('email',"");
            $message = $request->request->get('message',"");             

            //  Un usuario se ha puesto en contacto con nosotros
            if (strcmp($name,"") != 0) {
                try {
                    $message = \Swift_Message::newInstance()
                        ->setSubject('Rising - Formulario de contacto')
                        ->setFrom($email)
                        ->setTo('info@rising.es')
                        ->setBody('Un usuario ha contactado con nosotros. ' .
                        'Nombre: ' . $name . '. Email: ' . $email . 
                        '. Mensaje: ' . $message);

                    //  Hemos recibido el correo
                    if ($this->get('mailer')->send($message) !== false) {
			$translated = $this->get('translator')->trans('Gracias por contactar con nosotros. Te responderemos en un plazo máximo de 48 horas.', array(), 'contact');                        
			//$message = "Gracias por contactar con nosotros. Te responderemos en un plazo máximo de 48 horas.";
                        return $this->render('RisingBundle:Plantillas:contacto.html.twig', array(
                            'success' => $translated,
                        ));
                    }

                    //  No hemos recibido el correo
                    else {
			$translated = $this->get('translator')->trans('No hemos podido recibir tu mensaje. Por favor, inténtalo de nuevo más tarde. Disculpa las molestias.', array(), 'contact');
                        //$message = "No hemos podido recibir tu mensaje. Por favor, inténtalo de nuevo más tarde. Disculpa las molestias.";
                        return $this->render('RisingBundle:Plantillas:contacto.html.twig', array(
                            'error' => $translated,
                        ));
                    }
                }
                catch (\Exception $e) {
		    $translated = $this->get('translator')->trans('Hubo un problema que nos impidió recibir tu mensaje. Por favor, inténtalo de nuevo más tarde. Disculpa las molestias.', array(), 'contact');
                    //$message = "Hubo un problema que nos impidió recibir tu mensaje. Por favor, inténtalo de nuevo más tarde. Disculpa las molestias.";
                    return $this->render('RisingBundle:Plantillas:contacto.html.twig', array(
                        'error' => $translated,
                    ));
                }
            }

            //  Acceso normal
            else {
                return $this->render('RisingBundle:Plantillas:contacto.html.twig');
            }
        }

 public function contacto_enAction(Request $request)
        {
	    $locale = $request->getLocale();
	    $request->setLocale($locale);
            $name = $request->request->get('name',"");
            $email = $request->request->get('email',"");
            $message = $request->request->get('message',"");             

            //  Un usuario se ha puesto en contacto con nosotros
            if (strcmp($name,"") != 0) {
                try {
                    $message = \Swift_Message::newInstance()
                        ->setSubject('Rising - Formulario de contacto')
                        ->setFrom($email)
                        ->setTo('info@rising.es')
                        ->setBody('Un usuario ha contactado con nosotros. ' .
                        'Nombre: ' . $name . '. Email: ' . $email . 
                        '. Mensaje: ' . $message);

                    //  Hemos recibido el correo
                    if ($this->get('mailer')->send($message) !== false) {
			$translated = $this->get('translator')->trans('Gracias por contactar con nosotros. Te responderemos en un plazo máximo de 48 horas.', array(), 'contact');                        
			//$message = "Gracias por contactar con nosotros. Te responderemos en un plazo máximo de 48 horas.";
                        return $this->render('RisingBundle:Plantillas:contacto_en.html.twig', array(
                            'success' => $translated,
                        ));
                    }

                    //  No hemos recibido el correo
                    else {
			$translated = $this->get('translator')->trans('No hemos podido recibir tu mensaje. Por favor, inténtalo de nuevo más tarde. Disculpa las molestias.', array(), 'contact');
                        //$message = "No hemos podido recibir tu mensaje. Por favor, inténtalo de nuevo más tarde. Disculpa las molestias.";
                        return $this->render('RisingBundle:Plantillas:contacto_en.html.twig', array(
                            'error' => $translated,
                        ));
                    }
                }
                catch (\Exception $e) {
		    $translated = $this->get('translator')->trans('Hubo un problema que nos impidió recibir tu mensaje. Por favor, inténtalo de nuevo más tarde. Disculpa las molestias.', array(), 'contact');
                    //$message = "Hubo un problema que nos impidió recibir tu mensaje. Por favor, inténtalo de nuevo más tarde. Disculpa las molestias.";
                    return $this->render('RisingBundle:Plantillas:contacto_en.html.twig', array(
                        'error' => $translated,
                    ));
                }
            }

            //  Acceso normal
            else {
                return $this->render('RisingBundle:Plantillas:contacto_en.html.twig');
            }
        }
    }
?>
