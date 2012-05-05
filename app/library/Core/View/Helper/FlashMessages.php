<?php
class Core_View_Helper_FlashMessages
{
        public function flashMessages($translator = NULL)
        {
                $messages = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')->getMessages();
                $statMessages = array();
                $defaultStatus = 'error';
                $output = '';
                if (count($messages) > 0)
                {
                        foreach ($messages as $message)
                        {
                                if (!is_array($message) || !array_key_exists('status', $message)) {
                                    $statMessages[$defaultStatus] = array();

                                    if ($translator != NULL && $translator instanceof Zend_Translate)
                                        array_push($statMessages[$defaultStatus], $translator->_($message));
                                    else
                                        array_push($statMessages[$defaultStatus], $message);
                                } else {
                                    if (!array_key_exists($message['status'], $statMessages))
                                            $statMessages[$message['status']] = array();

                                    if ($translator != NULL && $translator instanceof Zend_Translate)
                                        array_push($statMessages[$message['status']], $translator->_($message['message']));
                                    else
                                        array_push($statMessages[$message['status']], $message['message']);
                                }
                        }
                        
                        foreach ($statMessages as $status => $messages)
                        {
                                $output .= '<div class="' . $status . '">';
                                if (count($messages) == 1)
                                        $output .=  $messages[0];
                                        
                                else
                                {
                                        $output .= '<ul>';
                                        foreach ($messages as $message)
                                                $output .= '<li>' . $message . '</li>';
                                        $output .= '</ul>';
                                }
                                
                                $output .= '</div>';
                        }
                        
                        return $output;
                }
                
        }
}