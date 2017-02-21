<?php

namespace HistoryBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("name", TextType::class, array(
                "label" => "Nom",
                "attr" => array(
                    "pattern"     => ".{2,}" //minlength
                )
            ))
            ->add("email", EmailType::class, array(
                "label" => "Email",
            ))
            ->add("subject", ChoiceType::class, array(
                "label" => "Sujet",
                "choices"  => array(
                    "Contacter l'auteur du site" => "Contact",
                    "Signaler sur la fiche d'une personnalité historique" => "Erreur",
                    "Rapporter un bug" => "Bug",
                ),
            ))
            ->add("message", TextareaType::class, array(
                "label" => "Message"
            ))
            ->add("save", SubmitType::class, array(
                "label" => "Envoyer",
                "attr" => array(
                    "class" => "btn btn-success"
                )
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $collectionConstraint = new Collection(array(
            "name" => array(
                new NotBlank(array("message" => "Name should not be blank.")),
                new Length(array("min" => 2))
            ),
            "email" => array(
                new NotBlank(array("message" => "Email should not be blank.")),
                new Email(array("message" => "Invalid email address."))
            ),
            "subject" => array(
                new NotBlank(array("message" => "Subject should not be blank.")),
                new Length(array("min" => 3))
            ),
            "message" => array(
                new NotBlank(array("message" => "Message should not be blank.")),
                new Length(array("min" => 5))
            )
        ));

        $resolver->setDefaults(array(
            "constraints" => $collectionConstraint
        ));
    }

    public function getName()
    {
        return "contact";
    }
}