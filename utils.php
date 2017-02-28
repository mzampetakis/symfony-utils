
    /**
     * Remove tones of a given string
     * @param $text
     * @return string
     */
    function remove_tones($text) {
        $special = array('ά', 'Ά', 'έ', 'Έ', 'ή', 'Ή', 'ί', 'Ί', 'ΐ', '΅Ι', 'ό', 'Ό', 'ύ', 'Ύ', 'ϋ', '΅Υ', 'ώ', "¨Ω");
        $simple = array('α', 'Α', 'ε', 'Ε', 'η', 'Η', 'ι', 'Ι', 'ϊ', 'Ϊ', 'ο', 'Ο', 'υ', 'Υ', 'υ', 'Υ', 'ω', "Ω");
        $output = str_replace($special, $simple, $text);
        return $output;
    }

    /**
     * activeLocale can be 0|1 or en|el ...
     * @param $translations
     * @param $transFieldToMatch
     * @param $activeLocale
     * @param $fieldToRender
     * @param $strictLang=0
     * @return mixed
     */
    public function renderTranslation($translations, $transFieldToMatch, $activeLocale, $fieldToRender, $strictLang = 0)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $em->clear();
        if ($translations && count($translations) > 0) {
            foreach ($translations as $translation):
                $var = 'get' . $transFieldToMatch;
                if (is_numeric($activeLocale))
                    $lang_type = "getId";
                else {
                    $lang_type = "getLangCode";
                    $activeLocale = strtoupper($activeLocale);
                }
                if (is_object($translation->$var()) && $translation->$var()->$lang_type() == $activeLocale) {
                    $var = 'get' . $fieldToRender;
                    return $translation->$var();
                }
            endforeach;
            $var = 'get' . $fieldToRender;
            if ($strictLang == 0) {
                if (isset($translations[0]) && is_object($translations[0])) {
                    return $translations[0]->$var();
                }
            }
            return '';
        }
    }


    /**
     * @param $fineName
     * @return mixed
     */
    public function getFileThumb($fineName){
        $name = explode('.', $fineName);
        return(substr($fineName, 0, -(strlen(end($name)) + 1)) . "_thumb." . end($name));

    }



    /**
     * @param $fineName
     * @param $size
     * @return mixed
     */
    public function getFileThumbSize($fineName, $size){
        $name = explode('.', $fineName);
        return(substr($fineName, 0, -(strlen(end($name)) + 1)) . "_square_thumb_".$size."." . end($name));

    }


    /**
     * @param $fields
     * @param $sort
     * @param $entityMany
     * @param $limit=null
     * @return mixed
     */
    public function getManyResults($fields, $sort, $entityMany, $limit=null){
        $em = $this->container->get('doctrine.orm.entity_manager');
        $em->clear();
        $results = $em->getRepository('AppBundle:'.$entityMany)->findBy($fields,$sort, $limit);

        return $results;
    }


    /**
     * @param $fields
     * @param $sort
     * @param $entity
     * @return mixed
     */
    public function getOneResult($fields, $sort, $entity){
        $em = $this->container->get('doctrine.orm.entity_manager');
        $em->clear();
        $results = $em->getRepository('AppBundle:'.$entity)->findBy($fields,$sort);

        if (count($results)>0)
            return $results[0];
        return null;
    }
