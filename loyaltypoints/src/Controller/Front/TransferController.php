// src/Controller/Front/TransferController.php
public function processAction(Request $request)
{
    $customer = $this->get('prestashop.adapter.customer.data_provider')->getCurrentCustomer();
    $form = $this->createForm(TransferPointsType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $data = $form->getData();
        
        // Validate recipient exists
        $recipient = $this->customerRepository->findByEmail($data['recipient_email']);
        if (!$recipient) {
            return new JsonResponse(['error' => 'Recipient not found'], 400);
        }

        // Validate transfer amount
        $errors = $this->transferValidator->validateTransfer(
            $customer,
            $recipient,
            $data['points']
        );

        if (count($errors) > 0) {
            return new JsonResponse(['errors' => $errors], 400);
        }

        // Process transfer
        $success = $this->pointsManager->transferPoints(
            $customer,
            $recipient,
            $data['points']
        );

        return $success 
            ? new JsonResponse(['success' => true])
            : new JsonResponse(['error' => 'Transfer failed'], 500);
    }

    return new JsonResponse(['errors' => (string) $form->getErrors(true, false)], 400);
}