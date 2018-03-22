<?php
/**
 * Created by PhpStorm.
 * User: pierr
 * Date: 22/03/2018
 * Time: 21:46
 */

namespace UserBundle\Controller;

use Cocur\Slugify\Slugify;
use DalBundle\Entity\Classe;
use DalBundle\Entity\UploadDocument;
use DalBundle\Entity\Utilisateur;
use DalBundle\Form\UploadDocumentType;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ImportController extends Controller
{
    /**
     * @var Request
     */
    private $request;
    private $inController = false;

    public function ajaxAction(Request $request)
    {
        $this->inController = true;

        $this->request = $request;
        $document = $this->getDoctrine()->getRepository("DalBundle:UploadDocument")->findOneBy([
            "import" => $request->get("import")
        ]);
        return new JsonResponse($this->import($document, $request));

    }

    private function import(UploadDocument $document, Request $request)
    {
        switch ($document->getImport()) {
            case "onde":
                return $this->ImportOnde($request,$document, $document->getAbsolutePath(), $document->getImportBy(), $this->request->get("page", 0));
                break;

        }
        return true;
    }

    public function ImportOnde(Request $request, UploadDocument $document, $documentAbsPath, $importBy = 5, $page = 0)
    {
        $line = -1;
        $added = array();
        $warning = array();
        $notfound = array();
        $em = $this->getDoctrine()->getManager();


        if (($handle = fopen($documentAbsPath, "r")) !== FALSE) {
            while (($row = fgetcsv($handle, 10000, ";")) !== FALSE) {
                $this->rowToUtf8($row);
                $this->rowCleanUp($row);

                try {
                    if ($line < $page * $importBy) {
                        $line++;
                        continue;
                    }
                    if ($line >= $page * intval($importBy) + intval($importBy)) {
                        break;
                    }

                    $nom = utf8_encode(trim($row[0]));
                    $prenom = utf8_encode(trim($row[2]));
                    $classe = trim($row[15]);
                    $cls = $em->getRepository('DalBundle:Classe')->findOneBy(['libelle'=>$classe]);

                    $user = $em->getRepository('DalBundle:Utilisateur')->findOneBy([
                        'nom' => $nom,
                        'prenom' => $prenom,
                    ]);
                    if ($user == null) {
                        $user = new Utilisateur();
                        $user->setNom($nom);
                        $user->setPrenom($prenom);
                        $slugify = new Slugify();
                        $username = $slugify->slugify($nom . ' ' . $prenom, '.');
                        $user->setUsername($username);
                        $user->setPlainPassword("bibliole");
                        $user->setEmail($username . '@' . $request->getHost());
                        $em->persist($user);
                    }

                    if ($cls === null) {
                        $cls = new Classe();
                        $cls->setLibelle($classe);
                        $em->persist($cls);
                    }
                    $user->setClasse($cls);
                    $cls->addUtilisateur($user);
                    $em->flush();
                    $added[] = $nom." ".$prenom." - ".$cls->getLibelle();
                    $line++;
                    $em->flush();
                } catch (Exception $e) {
                    $notfound[] = "Ligne " . $line . ". Erreur Exception : " . $e->getMessage();
                }
            }
        } else {
            $error[] = "Fichier non trouvé";
        }
        foreach ($notfound AS $k => $v) {
            $notfound[$k] = ($v);
        }
        foreach ($added AS $k => $v) {
            $added[$k] = ($v);
        }
        foreach ($warning AS $k => $v) {
            $warning[$k] = ($v);
        }
        if( $line >= $this->count($documentAbsPath)){
            unlink($documentAbsPath);
            $em->remove($document);
            $em->flush();
        }
        return array("response" => $line < $this->count($documentAbsPath), "success" => $added, "warning" => $warning, 'error' => $notfound, "line" => $line);

    }

    private function rowToUtf8($row)
    {
        foreach ($row AS $key => $str) {
            if (mb_detect_encoding($str, 'UTF-8', true) === false) {
                $row[$key] = ($str);
            }
        }
        return $row;
    }

    private function rowCleanUp($row)
    {
        foreach ($row AS $key => $str) {
            $row[$key] = trim($str);
        }
        return $row;

    }


    private function count($path)
    {
        $i = -1;
//        $fp = file($path);
        if (($handle = fopen($path, "r")) !== FALSE) {
            while (($row = fgetcsv($handle, 10000, ";", '"')) !== FALSE) {
                if (!empty($row)) {
                    $i++;
                }
            }
        }
        return $i;
    }

    public function newAction(Request $request)
    {
        $this->request = $request;
        $document = new UploadDocument();
        $form = $this->createForm(UploadDocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $document->upload();
            $document->setImportBy(1);
            $document->setUploader($this->getUser());
            $em->persist($document);
            $em->flush();
            $total = $this->count($document->getAbsolutePath());
            return $this->redirect($this->generateUrl("admin_import_wait", array("total" => $total, "import" => $document->getImport())));
        }

        return $this->render('@User/Default/import.html.twig', array(
            'document' => $document,
            'form' => $form->createView(),
        ));
    }

    public function waitAction(Request $request)
    {
        return $this->render("@User/Default/wait.html.twig", array("import" => $request->get("import")));
    }

}
